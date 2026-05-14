<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\ContributorMovement;
use App\Models\Circular;
use App\Models\Booking;
use App\Models\Document;
use App\Models\IndependentPurchaseOrder;
use App\Models\Meeting;
use App\Models\MeetingAttachment;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use App\Models\Regulation;
use App\Models\SellShares;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SharesPO;
use App\Models\TradingPeriod;
use App\Services\BuyerPenaltyService;
use App\Services\ParticipantAudienceResolver;
use App\Services\SellShareAnnualLimitService;
use App\Services\TradingWindowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContributorPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function (Request $request, $next) {
            abort_unless($this->contributor(), 403, 'يجب ربط حسابك ببيانات مساهم للوصول إلى هذه الصفحة.');

            return $next($request);
        });
    }

    public function dashboard(): View
    {
        $contributor = $this->contributor();
        $stats = $this->shareStats($contributor);

        $sellOffersCount = $contributor->sellShares()->count();
        $purchaseOrdersCount = $contributor->sharesPOs()->count()
            + $contributor->independentPurchaseOrders()->count();
        $movementsCount = ContributorMovement::query()
            ->where('from_contributor_id', $contributor->id)
            ->orWhere('to_contributor_id', $contributor->id)
            ->count();
        $newsCount = $this->newsQuery()->count();
        $filesCount = $this->filesQuery()->count();
        $regulationsCount = $this->regulationsQuery()->count();
        $serviceRequestsCount = Booking::query()
            ->where('user_id', auth()->id())
            ->count();
        $latestNews = $this->newsQuery()
            ->latest()
            ->limit(3)
            ->get();

        return view('contributor-portal.dashboard', compact(
            'contributor',
            'stats',
            'sellOffersCount',
            'purchaseOrdersCount',
            'movementsCount',
            'newsCount',
            'filesCount',
            'regulationsCount',
            'serviceRequestsCount',
            'latestNews'
        ));
    }

    public function statement(): View
    {
        $contributor = $this->contributor();
        $stats = $this->shareStats($contributor);

        $sellShares = $contributor->sellShares()
            ->with(['sharesPOs.contributor'])
            ->latest('insert_date')
            ->get();

        $purchaseOrders = $contributor->sharesPOs()
            ->with(['sellShare.seller'])
            ->latest('insert_date')
            ->get();

        $independentPurchaseOrders = $contributor->independentPurchaseOrders()
            ->latest('requested_at')
            ->get();

        $movements = ContributorMovement::query()
            ->with(['fromContributor', 'toContributor'])
            ->where(function ($query) use ($contributor): void {
                $query
                    ->where('from_contributor_id', $contributor->id)
                    ->orWhere('to_contributor_id', $contributor->id);
            })
            ->latest('date')
            ->get();

        return view('contributor-portal.statement', compact(
            'contributor',
            'stats',
            'sellShares',
            'purchaseOrders',
            'independentPurchaseOrders',
            'movements'
        ));
    }

    public function sellOffers(): View
    {
        $contributor = $this->contributor();
        $stats = $this->shareStats($contributor);
        $canCreate = app(TradingWindowService::class)->canCreateMarketEntry();
        $currentPhase = app(TradingWindowService::class)->currentPhase();

        $sellShares = $contributor->sellShares()
            ->withCount('sharesPOs')
            ->latest('insert_date')
            ->paginate(10);

        return view('contributor-portal.sell-offers', compact('contributor', 'stats', 'canCreate', 'currentPhase', 'sellShares'));
    }

    public function createSellOffer(): View
    {
        $contributor = $this->contributor();
        $availableShares = app(SellShareAnnualLimitService::class)->remaining($contributor);

        return view('contributor-portal.sell-offer-create', compact('contributor', 'availableShares'));
    }

    public function storeSellOffer(Request $request): RedirectResponse
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن إنشاء عرض بيع خارج مراحل العرض والصفقات الخاصة.');

        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
        ]);

        $contributor = $this->contributor();
        app(SellShareAnnualLimitService::class)->assertWithinLimit($contributor, (float) $validated['count']);

        $validated['user_id'] = $contributor->id;
        $validated['insert_date'] = now();
        $validated['ad_status'] = SellShares::AD_STATUS_INITIAL;

        SellShares::create($validated);

        return redirect()->route('contributor.sell-offers')
            ->with('success', 'تم إنشاء عرض البيع بنجاح.');
    }

    public function editSellOffer(SellShares $sellShare): View
    {
        $this->authorizeOwnSellOffer($sellShare);

        if (!$this->canEditSellOffer($sellShare)) {
            abort(403, $this->sellOfferEditBlockedMessage($sellShare));
        }

        $contributor = $this->contributor();
        $availableShares = app(SellShareAnnualLimitService::class)->remaining($contributor, $sellShare);

        return view('contributor-portal.sell-offer-edit', compact('contributor', 'sellShare', 'availableShares'));
    }

    public function updateSellOffer(Request $request, SellShares $sellShare): RedirectResponse
    {
        $this->authorizeOwnSellOffer($sellShare);

        if (!$this->canEditSellOffer($sellShare)) {
            throw ValidationException::withMessages([
                'count' => $this->sellOfferEditBlockedMessage($sellShare),
            ]);
        }

        $validated = $request->validate([
            'count' => 'required|numeric|min:1',
            'amount_per_share' => 'required|numeric|min:0.01',
            'end_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:100',
        ]);

        $contributor = $this->contributor();
        app(SellShareAnnualLimitService::class)->assertWithinLimit($contributor, (float) $validated['count'], $sellShare);

        $sellShare->update($validated);

        return redirect()->route('contributor.sell-offers')
            ->with('success', 'تم تحديث عرض البيع بنجاح.');
    }

    public function purchaseOrders(): View
    {
        $contributor = $this->contributor();

        $purchaseOrders = $contributor->sharesPOs()
            ->with(['sellShare.seller'])
            ->latest('insert_date')
            ->paginate(10);

        $independentPurchaseOrders = $contributor->independentPurchaseOrders()
            ->latest('requested_at')
            ->paginate(10, ['*'], 'independent_page');

        return view('contributor-portal.purchase-orders', compact('contributor', 'purchaseOrders', 'independentPurchaseOrders'));
    }

    public function createPurchaseOrder(): View
    {
        $contributor = $this->contributor();
        $currentPhase = app(TradingWindowService::class)->currentPhase();
        $hideSeller = $currentPhase === TradingPeriod::PHASE_OFFER;
        $stock = (float) Setting::getValue('base_price', 0);

        $sellShares = SellShares::query()
            ->with('seller')
            ->where('ad_status', '!=', SellShares::AD_STATUS_CANCELLED)
            ->where('user_id', '!=', $contributor->id)
            ->latest('insert_date')
            ->get();

        return view('contributor-portal.purchase-order-create', compact('contributor', 'sellShares', 'hideSeller', 'stock'));
    }

    public function storePurchaseOrder(Request $request): RedirectResponse
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن تقديم طلب شراء خارج مراحل العرض والصفقات الخاصة.');

        $basePrice = (float) Setting::getValue('base_price', 0);
        $validated = $request->validate([
            'sale_number' => 'required|exists:sell_shares,id',
            'count' => 'required|numeric|min:0.01',
            'amount_per_share' => 'required|numeric|min:' . max($basePrice, 0),
        ]);

        $contributor = $this->contributor();
        app(BuyerPenaltyService::class)->assertCanTrade($contributor);
        $offer = SellShares::findOrFail($validated['sale_number']);

        if ((int) $offer->ad_status === SellShares::AD_STATUS_CANCELLED) {
            throw ValidationException::withMessages(['sale_number' => 'عرض البيع مغلق ولا يقبل طلبات شراء جديدة.']);
        }

        if ((int) $offer->user_id === (int) $contributor->id) {
            throw ValidationException::withMessages(['sale_number' => 'لا يمكنك تقديم طلب شراء على عرض البيع الخاص بك.']);
        }

        SharesPO::create([
            'user_id' => $contributor->id,
            'sale_number' => $offer->id,
            'count' => $validated['count'],
            'amount_per_share' => $validated['amount_per_share'],
            'accept' => false,
            'insert_date' => now(),
            'po_status' => SharesPO::PO_STATUS_PENDING,
        ]);

        return redirect()->route('contributor.purchase-orders')
            ->with('success', 'تم تقديم طلب الشراء بنجاح.');
    }

    public function storeIndependentPurchaseOrder(Request $request): RedirectResponse
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن تقديم طلب شراء خارج مراحل العرض والصفقات الخاصة.');

        $basePrice = (float) Setting::getValue('base_price', 0);
        $validated = $request->validate([
            'count' => 'required|numeric|min:0.01',
            'amount_per_share' => 'required|numeric|min:' . max($basePrice, 0),
            'notes' => 'nullable|string|max:500',
        ]);

        $contributor = $this->contributor();
        app(BuyerPenaltyService::class)->assertCanTrade($contributor);

        IndependentPurchaseOrder::create([
            'contributor_id' => $contributor->id,
            'count' => $validated['count'],
            'amount_per_share' => $validated['amount_per_share'],
            'notes' => $validated['notes'] ?? null,
            'status' => IndependentPurchaseOrder::STATUS_PENDING,
            'requested_at' => now(),
        ]);

        return redirect()->route('contributor.purchase-orders')
            ->with('success', 'تم تقديم طلب الشراء المستقل بنجاح.');
    }

    public function polls(): View
    {
        $polls = $this->contributorPollsQuery()
            ->with(['pollOptions', 'pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->latest('created_date')
            ->paginate(10);

        return view('contributor-portal.polls', compact('polls'));
    }

    public function showPoll(Poll $poll): View
    {
        $this->authorizePollAccess($poll);

        $poll->load(['pollOptions', 'pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())]);
        $userHasVoted = $poll->pollAnswers->isNotEmpty();
        $canVote = $poll->isCurrentlyActive() && !$userHasVoted && $poll->pollOptions->isNotEmpty();

        return view('contributor-portal.poll-show', compact('poll', 'userHasVoted', 'canVote'));
    }

    public function votePoll(Request $request, Poll $poll): RedirectResponse
    {
        $this->authorizePollAccess($poll);

        if (!$poll->isCurrentlyActive()) {
            return redirect()->back()->with('error', 'هذا الاستطلاع غير نشط حاليا.');
        }

        if (PollAnswer::query()->where('poll_id', $poll->id)->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'لقد أجبت على هذا الاستطلاع من قبل.');
        }

        $validated = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        $pollOption = PollOption::query()
            ->where('poll_id', $poll->id)
            ->findOrFail($validated['poll_option_id']);

        PollAnswer::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $pollOption->id,
            'user_id' => auth()->id(),
            'answer_date' => now(),
        ]);

        $pollOption->increment('votes');

        return redirect()->route('contributor.polls.show', $poll)
            ->with('success', 'تم تسجيل إجابتك بنجاح.');
    }

    public function meetings(): View
    {
        $meetings = $this->contributorMeetingsQuery()
            ->latest('date')
            ->paginate(10);

        return view('contributor-portal.meetings', compact('meetings'));
    }

    public function news(): View
    {
        $news = $this->newsQuery()
            ->latest()
            ->paginate(10);

        return view('contributor-portal.news', compact('news'));
    }

    public function showNews(Circular $circular): View
    {
        $this->authorizeNewsAccess($circular);

        return view('contributor-portal.news-show', compact('circular'));
    }

    public function downloadNewsAttachment(Circular $circular)
    {
        $this->authorizeNewsAccess($circular);

        if (!$circular->file_path || !Storage::disk('public')->exists($circular->file_path)) {
            return redirect()
                ->route('contributor.news.show', $circular)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $circular->file_path,
            $circular->original_filename
        );
    }

    public function files(): View
    {
        $files = $this->filesQuery()
            ->with('meeting:id,name,date')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.files', compact('files'));
    }

    public function showFile(Document $document): View
    {
        $this->authorizeFileAccess($document);
        $document->load('meeting:id,name,date');

        return view('contributor-portal.file-show', compact('document'));
    }

    public function downloadFile(Document $document)
    {
        $this->authorizeFileAccess($document);

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()
                ->route('contributor.files.show', $document)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    public function regulations(): View
    {
        $regulations = $this->regulationsQuery()
            ->latest()
            ->paginate(10);

        return view('contributor-portal.regulations', compact('regulations'));
    }

    public function showRegulation(Regulation $regulation): View
    {
        $this->authorizeRegulationAccess($regulation);

        return view('contributor-portal.regulation-show', compact('regulation'));
    }

    public function downloadRegulation(Regulation $regulation)
    {
        $this->authorizeRegulationAccess($regulation);

        if (!$regulation->file_path || !Storage::disk('public')->exists($regulation->file_path)) {
            return redirect()
                ->route('contributor.regulations.show', $regulation)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $regulation->file_path,
            $regulation->original_filename
        );
    }

    public function services(): View
    {
        $serviceRequests = Booking::query()
            ->with('service')
            ->where('user_id', auth()->id())
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time')
            ->paginate(10);

        return view('contributor-portal.services', compact('serviceRequests'));
    }

    public function createServiceRequest(): View
    {
        $services = Service::orderBy('name')->get();

        return view('contributor-portal.service-request-create', compact('services'));
    }

    public function storeServiceRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['date'],
            'booking_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('contributor.services')
            ->with('success', 'تم إرسال طلب الخدمة بنجاح.');
    }

    public function boardDashboard(): View
    {
        $this->assertBoardMember();

        $contributor = $this->contributor();
        $boardMembersCount = Contributor::query()
            ->where('is_board_member', true)
            ->count();
        $meetingsCount = $this->boardMeetingsQuery()->count();
        $activePollsCount = $this->boardPollsQuery()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        $pendingPollsCount = $this->boardPollsQuery()
            ->whereDoesntHave('pollAnswers', fn ($query) => $query->where('user_id', auth()->id()))
            ->count();
        $nextMeeting = $this->boardMeetingsQuery()
            ->where('date', '>=', now())
            ->orderBy('date')
            ->first();
        $latestMeetings = $this->boardMeetingsQuery()
            ->withCount('attachments')
            ->latest('date')
            ->limit(3)
            ->get();
        $latestPolls = $this->boardPollsQuery()
            ->with(['pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->latest('created_date')
            ->limit(3)
            ->get();

        return view('contributor-portal.board.dashboard', compact(
            'contributor',
            'boardMembersCount',
            'meetingsCount',
            'activePollsCount',
            'pendingPollsCount',
            'nextMeeting',
            'latestMeetings',
            'latestPolls'
        ));
    }

    public function boardPolls(): View
    {
        $this->assertBoardMember();

        $polls = $this->boardPollsQuery()
            ->with(['pollOptions', 'pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->latest('created_date')
            ->paginate(10);

        return view('contributor-portal.board.polls', compact('polls'));
    }

    public function boardMeetings(): View
    {
        $this->assertBoardMember();

        $meetings = $this->boardMeetingsQuery()
            ->withCount('attachments')
            ->latest('date')
            ->paginate(10);

        return view('contributor-portal.board.meetings', compact('meetings'));
    }

    public function showBoardMeeting(Meeting $meeting): View
    {
        $this->assertBoardMember();
        $this->authorizeBoardMeetingAccess($meeting);

        $meeting->load(['attachments.uploader', 'users:id,name,email']);

        return view('contributor-portal.board.meeting-show', compact('meeting'));
    }

    public function downloadBoardMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->assertBoardMember();
        $this->authorizeBoardMeetingAccess($meeting);
        abort_unless((int) $attachment->meeting_id === (int) $meeting->id, 404);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('contributor.board.meetings.show', $meeting)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function boardMembers(): View
    {
        $this->assertBoardMember();

        $members = Contributor::query()
            ->with(['user:id,name,email', 'departments.parent', 'managedCompanies'])
            ->where('is_board_member', true)
            ->orderBy('name')
            ->paginate(12);

        return view('contributor-portal.board.members', compact('members'));
    }

    public function committeesDashboard(): View
    {
        $this->assertCommitteeMember();

        $contributor = $this->contributor();
        $committeeMemberships = $this->committeeMemberships();
        $committeeMembersCount = $this->committeeMembersQuery()->count();
        $meetingsCount = $this->committeesMeetingsQuery()->count();
        $activePollsCount = $this->committeesPollsQuery()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        $pendingPollsCount = $this->committeesPollsQuery()
            ->whereDoesntHave('pollAnswers', fn ($query) => $query->where('user_id', auth()->id()))
            ->count();
        $nextMeeting = $this->committeesMeetingsQuery()
            ->where('date', '>=', now())
            ->orderBy('date')
            ->first();
        $latestMeetings = $this->committeesMeetingsQuery()
            ->withCount('attachments')
            ->latest('date')
            ->limit(3)
            ->get();
        $latestPolls = $this->committeesPollsQuery()
            ->with(['pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->latest('created_date')
            ->limit(3)
            ->get();

        return view('contributor-portal.committees.dashboard', compact(
            'contributor',
            'committeeMemberships',
            'committeeMembersCount',
            'meetingsCount',
            'activePollsCount',
            'pendingPollsCount',
            'nextMeeting',
            'latestMeetings',
            'latestPolls'
        ));
    }

    public function committeesPolls(): View
    {
        $this->assertCommitteeMember();

        $polls = $this->committeesPollsQuery()
            ->with(['pollOptions', 'pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->latest('created_date')
            ->paginate(10);

        return view('contributor-portal.committees.polls', compact('polls'));
    }

    public function committeesMeetings(): View
    {
        $this->assertCommitteeMember();

        $meetings = $this->committeesMeetingsQuery()
            ->withCount('attachments')
            ->latest('date')
            ->paginate(10);

        return view('contributor-portal.committees.meetings', compact('meetings'));
    }

    public function showCommitteesMeeting(Meeting $meeting): View
    {
        $this->assertCommitteeMember();
        $this->authorizeCommitteesMeetingAccess($meeting);

        $meeting->load(['attachments.uploader', 'users:id,name,email']);

        return view('contributor-portal.committees.meeting-show', compact('meeting'));
    }

    public function downloadCommitteesMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->assertCommitteeMember();
        $this->authorizeCommitteesMeetingAccess($meeting);
        abort_unless((int) $attachment->meeting_id === (int) $meeting->id, 404);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('contributor.committees.meetings.show', $meeting)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function committeesMembers(): View
    {
        $this->assertCommitteeMember();

        $committeeMemberships = $this->committeeMemberships();
        $members = $this->committeeMembersQuery()
            ->with(['user:id,name,email', 'departments.parent', 'managedCompanies'])
            ->orderBy('name')
            ->paginate(12);

        return view('contributor-portal.committees.members', compact('members', 'committeeMemberships'));
    }

    private function contributor(): ?Contributor
    {
        return auth()->user()?->contributor;
    }

    private function newsQuery()
    {
        return Circular::query()
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function filesQuery()
    {
        return Document::query()
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function regulationsQuery()
    {
        return Regulation::query()
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function boardPollsQuery()
    {
        return Poll::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS)
            ->whereHas('referencedUsers', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function boardMeetingsQuery()
    {
        return Meeting::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS)
            ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function committeesPollsQuery()
    {
        $memberships = $this->committeeMemberships();

        return Poll::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_COMMITTEE)
            ->whereIn('audience_committee', $memberships->all())
            ->whereHas('referencedUsers', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function committeesMeetingsQuery()
    {
        $memberships = $this->committeeMemberships();

        return Meeting::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_COMMITTEE)
            ->whereIn('audience_committee', $memberships->all())
            ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function contributorPollsQuery()
    {
        return Poll::query()
            ->whereIn('audience_scope', $this->mainContributorAudienceScopes())
            ->whereHas('referencedUsers', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function contributorMeetingsQuery()
    {
        return Meeting::query()
            ->whereIn('audience_scope', $this->mainContributorAudienceScopes())
            ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function mainContributorAudienceScopes(): array
    {
        return [
            ParticipantAudienceResolver::SCOPE_MANUAL,
            ParticipantAudienceResolver::SCOPE_ALL_USERS,
            ParticipantAudienceResolver::SCOPE_ALL_CONTRIBUTORS,
            ParticipantAudienceResolver::SCOPE_COMPANY,
            ParticipantAudienceResolver::SCOPE_DEPARTMENT,
        ];
    }

    private function committeeMembersQuery()
    {
        $memberships = $this->committeeMemberships();
        $query = Contributor::query();

        if ($memberships->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($query) use ($memberships): void {
            foreach ($memberships as $membership) {
                $query->orWhereJsonContains('committee_memberships', $membership);

                $escapedNeedle = $this->escapedJsonLikeNeedle($membership);
                if ($escapedNeedle !== '') {
                    $query->orWhere('committee_memberships', 'like', '%' . $escapedNeedle . '%');
                }
            }
        });
    }

    private function escapedJsonLikeNeedle(string $value): string
    {
        $encoded = json_encode($value);

        if ($encoded === false || strlen($encoded) < 2) {
            return '';
        }

        return str_replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            substr($encoded, 1, -1)
        );
    }

    private function committeeMemberships()
    {
        return collect($this->contributor()?->committee_memberships ?? [])
            ->filter(fn ($membership) => in_array($membership, Contributor::committeeMembershipOptions(), true))
            ->values();
    }

    private function authorizeNewsAccess(Circular $circular): void
    {
        abort_unless(
            $circular->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الخبر غير مخصص لحسابك.'
        );
    }

    private function authorizeFileAccess(Document $document): void
    {
        abort_unless(
            $document->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الملف غير مخصص لحسابك.'
        );
    }

    private function authorizeRegulationAccess(Regulation $regulation): void
    {
        abort_unless(
            $regulation->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذه اللائحة غير مخصصة لحسابك.'
        );
    }

    private function assertBoardMember(): void
    {
        abort_unless(
            (bool) $this->contributor()?->is_board_member,
            403,
            'هذه الصفحة مخصصة لأعضاء مجلس الإدارة فقط.'
        );
    }

    private function authorizeBoardMeetingAccess(Meeting $meeting): void
    {
        abort_unless(
            $meeting->audience_scope === ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS
                && $meeting->users()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الاجتماع غير مخصص لحسابك.'
        );
    }

    private function assertCommitteeMember(): void
    {
        abort_unless(
            $this->committeeMemberships()->isNotEmpty(),
            403,
            'هذه الصفحة مخصصة لأعضاء اللجان فقط.'
        );
    }

    private function authorizeCommitteesMeetingAccess(Meeting $meeting): void
    {
        abort_unless(
            $meeting->audience_scope === ParticipantAudienceResolver::SCOPE_COMMITTEE
                && $this->committeeMemberships()->contains($meeting->audience_committee)
                && $meeting->users()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الاجتماع غير مخصص لحسابك.'
        );
    }

    private function shareStats(Contributor $contributor): array
    {
        $shares = (float) ($contributor->share_count_cr ?? 0);
        $totalShares = (float) Contributor::query()->sum('share_count_cr');
        $price = (float) Setting::getValue('base_price', 0);

        return [
            'shares' => $shares,
            'total_shares' => $totalShares,
            'share_price' => $price,
            'ownership_percentage' => $totalShares > 0 ? round(($shares / $totalShares) * 100, 4) : 0,
            'estimated_value' => $shares * $price,
        ];
    }

    private function authorizeOwnSellOffer(SellShares $sellShare): void
    {
        abort_unless((int) $sellShare->user_id === (int) $this->contributor()->id, 403);
    }

    private function authorizePollAccess(Poll $poll): void
    {
        abort_unless(
            $poll->referencedUsers()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الاستطلاع غير مخصص لحسابك.'
        );
    }

    private function canEditSellOffer(SellShares $sellShare): bool
    {
        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_OFFER) {
            return false;
        }

        if (in_array((int) $sellShare->ad_status, [SellShares::AD_STATUS_COMPLETED, SellShares::AD_STATUS_CANCELLED], true)) {
            return false;
        }

        return !$sellShare->sharesPOs()->exists();
    }

    private function sellOfferEditBlockedMessage(SellShares $sellShare): string
    {
        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_OFFER) {
            return 'لا يمكن تعديل الكمية أو السعر إلا خلال الفترة الأولى من العرض.';
        }

        if ($sellShare->sharesPOs()->exists()) {
            return 'لا يمكن تعديل العرض بعد وجود طلبات شراء مرتبطة به.';
        }

        return 'لا يمكن تعديل هذا العرض في حالته الحالية.';
    }
}
