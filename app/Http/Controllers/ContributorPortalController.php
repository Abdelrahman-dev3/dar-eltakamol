<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\ContributorMovement;
use App\Models\IndependentPurchaseOrder;
use App\Models\Meeting;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use App\Models\SellShares;
use App\Models\Setting;
use App\Models\SharesPO;
use App\Models\TradingPeriod;
use App\Services\BuyerPenaltyService;
use App\Services\SellShareAnnualLimitService;
use App\Services\TradingWindowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('contributor-portal.dashboard', compact(
            'contributor',
            'stats',
            'sellOffersCount',
            'purchaseOrdersCount',
            'movementsCount'
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
        $polls = Poll::with(['pollOptions', 'pollAnswers' => fn ($query) => $query->where('user_id', auth()->id())])
            ->whereHas('referencedUsers', fn ($query) => $query->where('users.id', auth()->id()))
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
        $meetings = Meeting::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()))
            ->latest('date')
            ->paginate(10);

        return view('contributor-portal.meetings', compact('meetings'));
    }

    private function contributor(): ?Contributor
    {
        return auth()->user()?->contributor;
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
