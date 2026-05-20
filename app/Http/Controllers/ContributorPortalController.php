<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\ContributorMovement;
use App\Models\Circular;
use App\Models\CircularAttachment;
use App\Models\Booking;
use App\Models\BookingMessage;
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
use App\Services\SellShareSettlementService;
use App\Services\TradingWindowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            ->limit(1)
            ->get();
        $nextMeeting = $this->contributorMeetingsQuery()
            ->withCount('attachments')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->first();
        $dashboardCharts = $this->dashboardCharts($contributor, $stats);

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
            'latestNews',
            'nextMeeting',
            'dashboardCharts'
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
            ->with('independentPurchaseOrder')
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

    public function showSellOffer(SellShares $sellShare): View
    {
        $this->authorizeOwnSellOffer($sellShare);

        $sellShare->load(['sharesPOs.contributor', 'settlement.allocations.buyer', 'companyPurchaseObligations', 'independentPurchaseOrder.contributor']);
        $canEditOffer = $this->canEditSellOffer($sellShare);
        $currentPhase = app(TradingWindowService::class)->currentPhase();
        $canSettleOffer = $this->canSettleOwnSellOffer($sellShare);
        $annualRemaining = app(SellShareAnnualLimitService::class)->remaining($this->contributor(), $sellShare);

        return view('contributor-portal.sell-offer-show', compact('sellShare', 'canEditOffer', 'canSettleOffer', 'currentPhase', 'annualRemaining'));
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
        $currentPhase = app(TradingWindowService::class)->currentPhase();

        $purchaseOrders = $contributor->sharesPOs()
            ->with(['sellShare.seller', 'sellShare.sharesPOs'])
            ->latest('insert_date')
            ->paginate(10);

        $independentPurchaseOrders = $contributor->independentPurchaseOrders()
            ->withCount('sellOffers')
            ->latest('requested_at')
            ->paginate(10, ['*'], 'independent_page');

        return view('contributor-portal.purchase-orders', compact('contributor', 'currentPhase', 'purchaseOrders', 'independentPurchaseOrders'));
    }

    public function updateOwnPurchaseOrderPrice(Request $request, SharesPO $sharesPO): RedirectResponse
    {
        $this->authorizeOwnPurchaseOrder($sharesPO);

        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_PROCESSING) {
            throw ValidationException::withMessages([
                'amount_per_share' => 'يمكن رفع سعر طلب الشراء خلال الفترة الثانية من التداول فقط.',
            ]);
        }

        if ($sharesPO->accept || in_array((int) $sharesPO->po_status, [SharesPO::PO_STATUS_COMPLETED, SharesPO::PO_STATUS_REJECTED], true)) {
            throw ValidationException::withMessages([
                'amount_per_share' => 'لا يمكن تعديل سعر طلب شراء مقبول أو مكتمل أو مرفوض.',
            ]);
        }

        $basePrice = (float) Setting::getValue('base_price', 0);
        $minimumPrice = max($basePrice, (float) $sharesPO->amount_per_share);

        if ($sharesPO->price_negotiation_requested_at && $sharesPO->sellShare) {
            $minimumPrice = max($minimumPrice, $this->highestActivePurchaseOrderPrice($sharesPO->sellShare));
        }

        $validated = $request->validate([
            'amount_per_share' => 'required|numeric|min:' . $minimumPrice,
        ]);

        if ((float) $validated['amount_per_share'] <= (float) $sharesPO->amount_per_share) {
            throw ValidationException::withMessages([
                'amount_per_share' => 'يجب أن يكون السعر الجديد أعلى من السعر الحالي.',
            ]);
        }

        $sharesPO->update([
            'amount_per_share' => $validated['amount_per_share'],
            'price_negotiation_requested_at' => null,
            'price_negotiation_message' => null,
        ]);

        return redirect()->back()->with('success', 'تم رفع سعر طلب الشراء بنجاح وسيظهر السعر الجديد للبائع.');
    }

    public function requestPurchaseOrderPriceIncrease(Request $request, SellShares $sellShare, SharesPO $sharesPO): RedirectResponse
    {
        $this->authorizeOwnSellOffer($sellShare);
        $this->assertPurchaseOrderBelongsToSellOffer($sellShare, $sharesPO);

        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_PROCESSING) {
            throw ValidationException::withMessages([
                'negotiation' => 'يمكن طلب رفع السعر خلال الفترة الثانية من التداول فقط.',
            ]);
        }

        if ($sharesPO->accept || in_array((int) $sharesPO->po_status, [SharesPO::PO_STATUS_COMPLETED, SharesPO::PO_STATUS_REJECTED], true)) {
            throw ValidationException::withMessages([
                'negotiation' => 'لا يمكن طلب تفاوض على طلب مقبول أو مكتمل أو مرفوض.',
            ]);
        }

        $highestPrice = $this->highestActivePurchaseOrderPrice($sellShare);

        if ((float) $sharesPO->amount_per_share >= $highestPrice) {
            throw ValidationException::withMessages([
                'negotiation' => 'هذا الطلب يملك أعلى سعر مقدم حالياً ولا يحتاج إلى طلب رفع السعر.',
            ]);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        $message = $validated['message'] ?? 'طلب البائع رفع سعر طلب الشراء لمجاراة أعلى سعر مقدم، وإلا قد يتم رفض الطلب قبل التسوية.';

        $sharesPO->update([
            'accept' => false,
            'accepted_count' => 0,
            'po_status' => SharesPO::PO_STATUS_PENDING,
            'price_negotiation_requested_at' => now(),
            'price_negotiation_message' => $message,
        ]);

        return redirect()->back()->with('success', 'تم إرسال طلب رفع السعر إلى المشتري.');
    }

    public function respondToSellOfferPurchaseOrder(Request $request, SellShares $sellShare, SharesPO $sharesPO): RedirectResponse
    {
        $this->authorizeOwnSellOffer($sellShare);
        $this->assertPurchaseOrderBelongsToSellOffer($sellShare, $sharesPO);

        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_PROCESSING) {
            throw ValidationException::withMessages([
                'decision' => 'يمكن قبول أو رفض طلبات الشراء خلال الفترة الثانية من التداول فقط.',
            ]);
        }

        if ((int) $sharesPO->po_status === SharesPO::PO_STATUS_COMPLETED) {
            throw ValidationException::withMessages([
                'decision' => 'لا يمكن تعديل طلب شراء مكتمل.',
            ]);
        }

        $validated = $request->validate([
            'decision' => 'required|in:accept,reject',
            'accepted_count' => 'nullable|required_if:decision,accept|numeric|min:0.01',
        ]);

        if ($validated['decision'] === 'reject') {
            $sharesPO->update([
                'accept' => false,
                'accepted_count' => 0,
                'po_status' => SharesPO::PO_STATUS_REJECTED,
                'price_negotiation_requested_at' => null,
                'price_negotiation_message' => null,
            ]);

            return redirect()->back()->with('success', 'تم رفض طلب الشراء بنجاح.');
        }

        if ((int) $sharesPO->po_status === SharesPO::PO_STATUS_REJECTED) {
            throw ValidationException::withMessages([
                'decision' => 'لا يمكن قبول طلب شراء مرفوض.',
            ]);
        }

        $highestPrice = (float) $sellShare->sharesPOs()
            ->where(function ($query): void {
                $query
                    ->whereNull('po_status')
                    ->orWhereNotIn('po_status', [
                        SharesPO::PO_STATUS_COMPLETED,
                        SharesPO::PO_STATUS_REJECTED,
                    ]);
            })
            ->max('amount_per_share');

        if ((float) $sharesPO->amount_per_share < $highestPrice) {
            throw ValidationException::withMessages([
                'decision' => 'لا يمكن قبول طلب بسعر أقل من أعلى سعر مقدم حالياً. يمكن انتظار رفع السعر أو رفض الطلب.',
            ]);
        }

        $acceptedCount = round((float) $validated['accepted_count'], 2);

        if ($acceptedCount > (float) $sharesPO->count) {
            throw ValidationException::withMessages([
                'accepted_count' => 'الكمية المقبولة لا يمكن أن تتجاوز كمية طلب الشراء.',
            ]);
        }

        $sharesPO->update([
            'accept' => true,
            'accepted_count' => $acceptedCount,
            'po_status' => SharesPO::PO_STATUS_REVIEW,
            'price_negotiation_requested_at' => null,
            'price_negotiation_message' => null,
        ]);

        return redirect()->back()->with('success', 'تم قبول طلب الشراء بنجاح.');
    }

    public function settleOwnSellOffer(SellShares $sellShare): RedirectResponse
    {
        $this->authorizeOwnSellOffer($sellShare);

        if (!$this->canSettleOwnSellOffer($sellShare)) {
            throw ValidationException::withMessages([
                'settlement' => 'يمكن تنفيذ تسوية عرض البيع خلال الفترة الثانية فقط، وللعروض غير المكتملة أو غير الملغاة.',
            ]);
        }

        $settlement = app(SellShareSettlementService::class)->settleBySeller($sellShare, auth()->id());

        return redirect()->route('contributor.sell-offers.show', $sellShare)
            ->with('success', 'تمت تسوية عرض البيع بعدد تخصيصات: ' . $settlement->allocations()->count());
    }

    public function buyOffers(): View
    {
        $contributor = $this->contributor();

        $orders = IndependentPurchaseOrder::query()
            ->with(['contributor'])
            ->withCount('sellOffers')
            ->where('status', IndependentPurchaseOrder::STATUS_PUBLISHED)
            ->where('contributor_id', '!=', $contributor->id)
            ->latest('published_at')
            ->paginate(10);

        return view('contributor-portal.buy-offers', compact('orders'));
    }

    public function showBuyOffer(IndependentPurchaseOrder $independentPurchaseOrder): View
    {
        $contributor = $this->contributor();

        abort_unless((int) $independentPurchaseOrder->status === IndependentPurchaseOrder::STATUS_PUBLISHED, 404);
        abort_if((int) $independentPurchaseOrder->contributor_id === (int) $contributor->id, 403);

        $independentPurchaseOrder->load(['contributor', 'sellOffers.seller']);
        $existingOffer = $independentPurchaseOrder->sellOffers
            ->firstWhere('user_id', $contributor->id);
        $availableShares = app(SellShareAnnualLimitService::class)->remaining($contributor);

        return view('contributor-portal.buy-offer-show', [
            'order' => $independentPurchaseOrder,
            'existingOffer' => $existingOffer,
            'availableShares' => $availableShares,
        ]);
    }

    public function storeIndependentSellOffer(Request $request, IndependentPurchaseOrder $independentPurchaseOrder): RedirectResponse
    {
        app(TradingWindowService::class)->assertMarketEntryAllowed('لا يمكن تقديم عرض بيع على طلب شراء مستقل خارج مراحل التداول المتاحة.');

        $contributor = $this->contributor();

        abort_unless((int) $independentPurchaseOrder->status === IndependentPurchaseOrder::STATUS_PUBLISHED, 404);
        abort_if((int) $independentPurchaseOrder->contributor_id === (int) $contributor->id, 403);

        if ($independentPurchaseOrder->sellOffers()->where('user_id', $contributor->id)->exists()) {
            throw ValidationException::withMessages([
                'count' => 'لديك عرض بيع مقدم على طلب الشراء هذا بالفعل.',
            ]);
        }

        $validated = $request->validate([
            'count' => 'required|numeric|min:0.01',
            'amount_per_share' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:100',
        ]);

        app(SellShareAnnualLimitService::class)->assertWithinLimit($contributor, (float) $validated['count']);

        SellShares::create([
            'count' => $validated['count'],
            'amount_per_share' => $validated['amount_per_share'],
            'notes' => $validated['notes'] ?? null,
            'insert_date' => now(),
            'ad_status' => SellShares::AD_STATUS_ACTIVE,
            'user_id' => $contributor->id,
            'independent_purchase_order_id' => $independentPurchaseOrder->id,
            'independent_offer_status' => SellShares::INDEPENDENT_STATUS_PENDING,
            'accepted_count' => 0,
        ]);

        return redirect()->route('contributor.buy-offers.show', $independentPurchaseOrder)
            ->with('success', 'تم تقديم عرض البيع وربطه بطلب الشراء المستقل بنجاح.');
    }

    public function showOwnIndependentPurchaseOrder(IndependentPurchaseOrder $independentPurchaseOrder): View
    {
        $this->authorizeOwnIndependentPurchaseOrder($independentPurchaseOrder);

        $independentPurchaseOrder->load(['contributor', 'sellOffers.seller']);
        $pendingOffersCount = $independentPurchaseOrder->sellOffers
            ->where('independent_offer_status', SellShares::INDEPENDENT_STATUS_PENDING)
            ->count();
        $acceptedShares = (float) $independentPurchaseOrder->sellOffers->sum(fn (SellShares $offer) => (float) $offer->accepted_count);
        $canClose = $independentPurchaseOrder->canBeClosed();

        return view('contributor-portal.purchase-order-show', [
            'order' => $independentPurchaseOrder,
            'pendingOffersCount' => $pendingOffersCount,
            'acceptedShares' => $acceptedShares,
            'canClose' => $canClose,
        ]);
    }

    public function respondToIndependentSellOffer(Request $request, IndependentPurchaseOrder $independentPurchaseOrder, SellShares $sellShare): RedirectResponse
    {
        $this->authorizeOwnIndependentPurchaseOrder($independentPurchaseOrder);
        $this->assertSellOfferBelongsToIndependentOrder($independentPurchaseOrder, $sellShare);

        if ((int) $independentPurchaseOrder->status !== IndependentPurchaseOrder::STATUS_PUBLISHED) {
            throw ValidationException::withMessages([
                'accepted_count' => 'لا يمكن الرد على عروض بيع لطلب غير منشور.',
            ]);
        }

        if ($sellShare->independent_offer_status !== SellShares::INDEPENDENT_STATUS_PENDING) {
            throw ValidationException::withMessages([
                'accepted_count' => 'تم الرد على عرض البيع هذا مسبقاً.',
            ]);
        }

        $validated = $request->validate([
            'decision' => 'required|in:accept,reject',
            'accepted_count' => 'nullable|required_if:decision,accept|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $independentPurchaseOrder, $sellShare): void {
            $order = IndependentPurchaseOrder::query()
                ->whereKey($independentPurchaseOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            $offer = SellShares::query()
                ->whereKey($sellShare->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['decision'] === 'reject') {
                $offer->update([
                    'independent_offer_status' => SellShares::INDEPENDENT_STATUS_REJECTED,
                    'accepted_count' => 0,
                    'responded_at' => now(),
                ]);

                $this->syncIndependentPurchaseOrderStatus($order);
                return;
            }

            $acceptedCount = round((float) $validated['accepted_count'], 2);

            if ($acceptedCount > (float) $offer->count) {
                throw ValidationException::withMessages([
                    'accepted_count' => 'الكمية المقبولة لا يمكن أن تتجاوز كمية عرض البيع.',
                ]);
            }

            $acceptedByOthers = (float) $order->sellOffers()
                ->where('id', '!=', $offer->id)
                ->sum('accepted_count');
            $remaining = max(0, (float) $order->count - $acceptedByOthers);

            if ($acceptedCount > $remaining) {
                throw ValidationException::withMessages([
                    'accepted_count' => 'الكمية المقبولة تتجاوز الكمية المتبقية في طلب الشراء.',
                ]);
            }

            $offer->update([
                'independent_offer_status' => $acceptedCount >= (float) $offer->count
                    ? SellShares::INDEPENDENT_STATUS_ACCEPTED
                    : SellShares::INDEPENDENT_STATUS_PARTIAL,
                'accepted_count' => $acceptedCount,
                'responded_at' => now(),
                'ad_status' => $acceptedCount >= (float) $offer->count
                    ? SellShares::AD_STATUS_COMPLETED
                    : SellShares::AD_STATUS_ACTIVE,
            ]);

            SharesPO::create([
                'user_id' => $order->contributor_id,
                'sale_number' => $offer->id,
                'count' => $acceptedCount,
                'accepted_count' => $acceptedCount,
                'amount_per_share' => $offer->amount_per_share,
                'accept' => true,
                'insert_date' => now(),
                'po_status' => SharesPO::PO_STATUS_REVIEW,
            ]);

            app(\App\Services\SellShareSettlementService::class)->settle($offer, auth()->id());

            $this->syncIndependentPurchaseOrderStatus($order);
        });

        return redirect()->route('contributor.purchase-orders.independent.show', $independentPurchaseOrder)
            ->with('success', 'تم حفظ ردك على عرض البيع بنجاح.');
    }

    public function closeOwnIndependentPurchaseOrder(IndependentPurchaseOrder $independentPurchaseOrder): RedirectResponse
    {
        $this->authorizeOwnIndependentPurchaseOrder($independentPurchaseOrder);

        $this->closeIndependentPurchaseOrder($independentPurchaseOrder);

        return redirect()->route('contributor.purchase-orders')
            ->with('success', 'تم إغلاق طلب الشراء المستقل بنجاح.');
    }

    public function createPurchaseOrder(): View
    {
        $contributor = $this->contributor();
        $currentPhase = app(TradingWindowService::class)->currentPhase();
        $hideSeller = $currentPhase === TradingPeriod::PHASE_OFFER;
        $canCreateLinkedPurchaseOrder = $currentPhase === TradingPeriod::PHASE_OFFER;
        $stock = (float) Setting::getValue('base_price', 0);

        $sellShares = SellShares::query()
            ->with('seller')
            ->where('ad_status', '!=', SellShares::AD_STATUS_CANCELLED)
            ->whereNull('independent_purchase_order_id')
            ->where('user_id', '!=', $contributor->id)
            ->latest('insert_date')
            ->get();

        return view('contributor-portal.purchase-order-create', compact('contributor', 'sellShares', 'hideSeller', 'canCreateLinkedPurchaseOrder', 'stock'));
    }

    public function storePurchaseOrder(Request $request): RedirectResponse
    {
        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_OFFER) {
            throw ValidationException::withMessages([
                'trading_period' => 'لا يمكن تقديم طلب شراء مرتبط بعرض بيع بعد انتهاء الفترة الأولى من التداول.',
            ]);
        }

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

        if ($offer->independent_purchase_order_id) {
            throw ValidationException::withMessages(['sale_number' => 'هذا عرض بيع مرتبط بطلب شراء مستقل ولا يقبل طلبات شراء عامة.']);
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
        $this->assertIndependentPurchaseOrderEntryAllowed();

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
            ->withCount('attachments')
            ->latest('date')
            ->paginate(10);

        return view('contributor-portal.meetings', compact('meetings'));
    }

    public function showMeeting(Meeting $meeting): View
    {
        $this->authorizeContributorMeetingAccess($meeting);

        $meeting->load(['attachments.uploader', 'polls.pollOptions', 'polls.pollAnswers', 'users:id,name,email']);

        return view('contributor-portal.meeting-show', compact('meeting'));
    }

    public function downloadMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->authorizeContributorMeetingAccess($meeting);
        abort_unless((int) $attachment->meeting_id === (int) $meeting->id, 404);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('contributor.meetings.show', $meeting)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function viewMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->authorizeContributorMeetingAccess($meeting);

        return $this->inlineMeetingAttachmentResponse($meeting, $attachment);
    }

    public function news(): View
    {
        $news = $this->newsQuery()
            ->withCount('attachments')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.news', compact('news'));
    }

    public function showNews(Circular $circular): View
    {
        $this->authorizeNewsAccess($circular);
        $circular->load('attachments');

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

    public function viewNewsAttachment(Circular $circular)
    {
        $this->authorizeNewsAccess($circular);

        if (!$circular->file_path || !Storage::disk('public')->exists($circular->file_path)) {
            return redirect()
                ->route('contributor.news.show', $circular)
                ->with('error', __('الملف غير موجود'));
        }

        return $this->inlineStorageResponse($circular->file_path, $circular->original_filename, $circular->file_type);
    }

    public function viewNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeNewsAccess($attachment->circular);

        return $this->inlineStorageResponse($attachment->file_path, $attachment->original_filename, $attachment->file_type);
    }

    public function downloadNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeNewsAccess($attachment->circular);

        if (!$attachment->file_path || !Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('contributor.news.show', $attachment->circular)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->original_filename
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
            ->latest()
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
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'booking_date' => now()->toDateString(),
            'booking_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? null,
            'status' => Booking::STATUS_RECEIVED,
        ]);

        if (filled($validated['notes'] ?? null)) {
            $booking->messages()->create([
                'user_id' => auth()->id(),
                'author_type' => BookingMessage::AUTHOR_CONTRIBUTOR,
                'message' => $validated['notes'],
            ]);
        }

        return redirect()->route('contributor.services')
            ->with('success', 'تم إرسال طلب الخدمة بنجاح.');
    }

    public function showServiceRequest(Booking $booking): View
    {
        $this->authorizeOwnServiceRequest($booking);

        $booking->load(['service', 'messages.user']);

        return view('contributor-portal.service-request-show', compact('booking'));
    }

    public function replyServiceRequest(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeOwnServiceRequest($booking);

        if ($booking->status === Booking::STATUS_COMPLETED) {
            throw ValidationException::withMessages([
                'message' => 'لا يمكن الرد على طلب خدمة مكتمل.',
            ]);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $booking->messages()->create([
            'user_id' => auth()->id(),
            'author_type' => BookingMessage::AUTHOR_CONTRIBUTOR,
            'message' => $validated['message'],
        ]);

        return redirect()->route('contributor.services.show', $booking)
            ->with('success', 'تم إرسال الرد بنجاح.');
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

    public function boardNews(): View
    {
        $this->assertBoardMember();

        $news = $this->boardNewsQuery()
            ->withCount('attachments')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.news', [
            'news' => $news,
            'pageTitle' => __('أخبار مجلس الإدارة'),
            'pageSubtitle' => __('الأخبار الموجهة لأعضاء مجلس الإدارة فقط.'),
            'newsShowRoute' => 'contributor.board.news.show',
            'newsViewRoute' => 'contributor.board.news.view',
            'newsDownloadRoute' => 'contributor.board.news.download',
        ]);
    }

    public function showBoardNews(Circular $circular): View
    {
        $this->authorizeBoardNewsAccess($circular);
        $circular->load('attachments');

        return view('contributor-portal.news-show', [
            'circular' => $circular,
            'newsBackRoute' => 'contributor.board.news',
            'newsViewRoute' => 'contributor.board.news.view',
            'newsDownloadRoute' => 'contributor.board.news.download',
            'newsAttachmentViewRoute' => 'contributor.board.news.attachments.view',
            'newsAttachmentDownloadRoute' => 'contributor.board.news.attachments.download',
        ]);
    }

    public function viewBoardNewsAttachment(Circular $circular)
    {
        $this->authorizeBoardNewsAccess($circular);

        return $this->inlineStorageResponse($circular->file_path, $circular->original_filename, $circular->file_type);
    }

    public function downloadBoardNewsAttachment(Circular $circular)
    {
        $this->authorizeBoardNewsAccess($circular);

        return $this->downloadStoredContent($circular, 'contributor.board.news.show');
    }

    public function viewBoardNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeBoardNewsAccess($attachment->circular);

        return $this->inlineStorageResponse($attachment->file_path, $attachment->original_filename, $attachment->file_type);
    }

    public function downloadBoardNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeBoardNewsAccess($attachment->circular);

        return $this->downloadStoredContent($attachment, 'contributor.board.news.show', $attachment->circular);
    }

    public function boardFiles(): View
    {
        $this->assertBoardMember();

        $files = $this->boardFilesQuery()
            ->with('meeting:id,name,date')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.files', [
            'files' => $files,
            'pageTitle' => __('ملفات مجلس الإدارة'),
            'pageSubtitle' => __('الملفات الموجهة لأعضاء مجلس الإدارة فقط.'),
            'fileShowRoute' => 'contributor.board.files.show',
            'fileDownloadRoute' => 'contributor.board.files.download',
        ]);
    }

    public function showBoardFile(Document $document): View
    {
        $this->authorizeBoardFileAccess($document);
        $document->load('meeting:id,name,date');

        return view('contributor-portal.file-show', [
            'document' => $document,
            'fileBackRoute' => 'contributor.board.files',
            'fileDownloadRoute' => 'contributor.board.files.download',
        ]);
    }

    public function downloadBoardFile(Document $document)
    {
        $this->authorizeBoardFileAccess($document);

        return $this->downloadStoredContent($document, 'contributor.board.files.show');
    }

    public function boardRegulations(): View
    {
        $this->assertBoardMember();

        $regulations = $this->boardRegulationsQuery()
            ->latest()
            ->paginate(10);

        return view('contributor-portal.regulations', [
            'regulations' => $regulations,
            'pageTitle' => __('لوائح مجلس الإدارة'),
            'pageSubtitle' => __('اللوائح الموجهة لأعضاء مجلس الإدارة فقط.'),
            'regulationShowRoute' => 'contributor.board.regulations.show',
            'regulationDownloadRoute' => 'contributor.board.regulations.download',
        ]);
    }

    public function showBoardRegulation(Regulation $regulation): View
    {
        $this->authorizeBoardRegulationAccess($regulation);

        return view('contributor-portal.regulation-show', [
            'regulation' => $regulation,
            'regulationBackRoute' => 'contributor.board.regulations',
            'regulationDownloadRoute' => 'contributor.board.regulations.download',
        ]);
    }

    public function downloadBoardRegulation(Regulation $regulation)
    {
        $this->authorizeBoardRegulationAccess($regulation);

        return $this->downloadStoredContent($regulation, 'contributor.board.regulations.show');
    }

    public function showBoardMeeting(Meeting $meeting): View
    {
        $this->assertBoardMember();
        $this->authorizeBoardMeetingAccess($meeting);

        $meeting->load(['attachments.uploader', 'polls.pollOptions', 'polls.pollAnswers', 'users:id,name,email']);

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

    public function viewBoardMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->assertBoardMember();
        $this->authorizeBoardMeetingAccess($meeting);

        return $this->inlineMeetingAttachmentResponse($meeting, $attachment);
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

    public function committeesNews(): View
    {
        $this->assertCommitteeMember();

        $news = $this->committeesNewsQuery()
            ->withCount('attachments')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.news', [
            'news' => $news,
            'pageTitle' => __('أخبار اللجان'),
            'pageSubtitle' => __('الأخبار الموجهة للجان التي تنتمي إليها.'),
            'newsShowRoute' => 'contributor.committees.news.show',
            'newsViewRoute' => 'contributor.committees.news.view',
            'newsDownloadRoute' => 'contributor.committees.news.download',
        ]);
    }

    public function showCommitteesNews(Circular $circular): View
    {
        $this->authorizeCommitteesNewsAccess($circular);
        $circular->load('attachments');

        return view('contributor-portal.news-show', [
            'circular' => $circular,
            'newsBackRoute' => 'contributor.committees.news',
            'newsViewRoute' => 'contributor.committees.news.view',
            'newsDownloadRoute' => 'contributor.committees.news.download',
            'newsAttachmentViewRoute' => 'contributor.committees.news.attachments.view',
            'newsAttachmentDownloadRoute' => 'contributor.committees.news.attachments.download',
        ]);
    }

    public function viewCommitteesNewsAttachment(Circular $circular)
    {
        $this->authorizeCommitteesNewsAccess($circular);

        return $this->inlineStorageResponse($circular->file_path, $circular->original_filename, $circular->file_type);
    }

    public function downloadCommitteesNewsAttachment(Circular $circular)
    {
        $this->authorizeCommitteesNewsAccess($circular);

        return $this->downloadStoredContent($circular, 'contributor.committees.news.show');
    }

    public function viewCommitteesNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeCommitteesNewsAccess($attachment->circular);

        return $this->inlineStorageResponse($attachment->file_path, $attachment->original_filename, $attachment->file_type);
    }

    public function downloadCommitteesNewsAttachmentFile(CircularAttachment $attachment)
    {
        $this->authorizeCommitteesNewsAccess($attachment->circular);

        return $this->downloadStoredContent($attachment, 'contributor.committees.news.show', $attachment->circular);
    }

    public function committeesFiles(): View
    {
        $this->assertCommitteeMember();

        $files = $this->committeesFilesQuery()
            ->with('meeting:id,name,date')
            ->latest()
            ->paginate(10);

        return view('contributor-portal.files', [
            'files' => $files,
            'pageTitle' => __('ملفات اللجان'),
            'pageSubtitle' => __('الملفات الموجهة للجان التي تنتمي إليها.'),
            'fileShowRoute' => 'contributor.committees.files.show',
            'fileDownloadRoute' => 'contributor.committees.files.download',
        ]);
    }

    public function showCommitteesFile(Document $document): View
    {
        $this->authorizeCommitteesFileAccess($document);
        $document->load('meeting:id,name,date');

        return view('contributor-portal.file-show', [
            'document' => $document,
            'fileBackRoute' => 'contributor.committees.files',
            'fileDownloadRoute' => 'contributor.committees.files.download',
        ]);
    }

    public function downloadCommitteesFile(Document $document)
    {
        $this->authorizeCommitteesFileAccess($document);

        return $this->downloadStoredContent($document, 'contributor.committees.files.show');
    }

    public function committeesRegulations(): View
    {
        $this->assertCommitteeMember();

        $regulations = $this->committeesRegulationsQuery()
            ->latest()
            ->paginate(10);

        return view('contributor-portal.regulations', [
            'regulations' => $regulations,
            'pageTitle' => __('لوائح اللجان'),
            'pageSubtitle' => __('اللوائح الموجهة للجان التي تنتمي إليها.'),
            'regulationShowRoute' => 'contributor.committees.regulations.show',
            'regulationDownloadRoute' => 'contributor.committees.regulations.download',
        ]);
    }

    public function showCommitteesRegulation(Regulation $regulation): View
    {
        $this->authorizeCommitteesRegulationAccess($regulation);

        return view('contributor-portal.regulation-show', [
            'regulation' => $regulation,
            'regulationBackRoute' => 'contributor.committees.regulations',
            'regulationDownloadRoute' => 'contributor.committees.regulations.download',
        ]);
    }

    public function downloadCommitteesRegulation(Regulation $regulation)
    {
        $this->authorizeCommitteesRegulationAccess($regulation);

        return $this->downloadStoredContent($regulation, 'contributor.committees.regulations.show');
    }

    public function showCommitteesMeeting(Meeting $meeting): View
    {
        $this->assertCommitteeMember();
        $this->authorizeCommitteesMeetingAccess($meeting);

        $meeting->load(['attachments.uploader', 'polls.pollOptions', 'polls.pollAnswers', 'users:id,name,email']);

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

    public function viewCommitteesMeetingAttachment(Meeting $meeting, MeetingAttachment $attachment)
    {
        $this->assertCommitteeMember();
        $this->authorizeCommitteesMeetingAccess($meeting);

        return $this->inlineMeetingAttachmentResponse($meeting, $attachment);
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
            ->whereIn('audience_scope', $this->mainContributorContentScopes())
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function filesQuery()
    {
        return Document::query()
            ->whereIn('audience_scope', $this->mainContributorContentScopes())
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function regulationsQuery()
    {
        return Regulation::query()
            ->whereIn('audience_scope', $this->mainContributorContentScopes())
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function boardNewsQuery()
    {
        return Circular::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS)
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function boardFilesQuery()
    {
        return Document::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS)
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function boardRegulationsQuery()
    {
        return Regulation::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS)
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function committeesNewsQuery()
    {
        return Circular::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_COMMITTEE)
            ->whereIn('audience_committee', $this->committeeMemberships()->all())
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function committeesFilesQuery()
    {
        return Document::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_COMMITTEE)
            ->whereIn('audience_committee', $this->committeeMemberships()->all())
            ->whereHas('recipients', fn ($query) => $query->where('users.id', auth()->id()));
    }

    private function committeesRegulationsQuery()
    {
        return Regulation::query()
            ->where('audience_scope', ParticipantAudienceResolver::SCOPE_COMMITTEE)
            ->whereIn('audience_committee', $this->committeeMemberships()->all())
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

    private function mainContributorContentScopes(): array
    {
        return [
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
            in_array($circular->audience_scope, $this->mainContributorContentScopes(), true)
                && $circular->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الخبر غير مخصص لحسابك.'
        );
    }

    private function authorizeBoardNewsAccess(Circular $circular): void
    {
        $this->assertBoardMember();

        abort_unless(
            $circular->audience_scope === ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS
                && $circular->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الخبر غير مخصص لحسابك.'
        );
    }

    private function authorizeCommitteesNewsAccess(Circular $circular): void
    {
        $this->assertCommitteeMember();

        abort_unless(
            $circular->audience_scope === ParticipantAudienceResolver::SCOPE_COMMITTEE
                && $this->committeeMemberships()->contains($circular->audience_committee)
                && $circular->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الخبر غير مخصص لحسابك.'
        );
    }

    private function inlineMeetingAttachmentResponse(Meeting $meeting, MeetingAttachment $attachment)
    {
        abort_unless((int) $attachment->meeting_id === (int) $meeting->id, 404);

        return $this->inlineStorageResponse($attachment->file_path, $attachment->file_name, $attachment->mime_type);
    }

    private function inlineStorageResponse(string $filePath, string $filename, ?string $storedMimeType = null)
    {
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', __('الملف غير موجود'));
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeType = $extension === 'pdf'
            ? 'application/pdf'
            : ($storedMimeType ?: Storage::disk('public')->mimeType($filePath) ?: 'application/octet-stream');

        $safeFilename = Str::ascii($filename);
        $safeFilename = $safeFilename !== '' ? $safeFilename : 'attachment.' . ($extension ?: 'bin');

        return response()->stream(function () use ($filePath): void {
            echo Storage::disk('public')->get($filePath);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($safeFilename) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function downloadStoredContent($content, string $fallbackRoute, $fallbackParameter = null)
    {
        if (!$content->file_path || !Storage::disk('public')->exists($content->file_path)) {
            return redirect()
                ->route($fallbackRoute, $fallbackParameter ?: $content)
                ->with('error', __('الملف غير موجود'));
        }

        return Storage::disk('public')->download(
            $content->file_path,
            $content->original_filename
        );
    }

    private function authorizeFileAccess(Document $document): void
    {
        abort_unless(
            in_array($document->audience_scope, $this->mainContributorContentScopes(), true)
                && $document->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الملف غير مخصص لحسابك.'
        );
    }

    private function authorizeBoardFileAccess(Document $document): void
    {
        $this->assertBoardMember();

        abort_unless(
            $document->audience_scope === ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS
                && $document->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الملف غير مخصص لحسابك.'
        );
    }

    private function authorizeCommitteesFileAccess(Document $document): void
    {
        $this->assertCommitteeMember();

        abort_unless(
            $document->audience_scope === ParticipantAudienceResolver::SCOPE_COMMITTEE
                && $this->committeeMemberships()->contains($document->audience_committee)
                && $document->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذا الملف غير مخصص لحسابك.'
        );
    }

    private function authorizeRegulationAccess(Regulation $regulation): void
    {
        abort_unless(
            in_array($regulation->audience_scope, $this->mainContributorContentScopes(), true)
                && $regulation->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذه اللائحة غير مخصصة لحسابك.'
        );
    }

    private function authorizeBoardRegulationAccess(Regulation $regulation): void
    {
        $this->assertBoardMember();

        abort_unless(
            $regulation->audience_scope === ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS
                && $regulation->recipients()->where('users.id', auth()->id())->exists(),
            403,
            'هذه اللائحة غير مخصصة لحسابك.'
        );
    }

    private function authorizeCommitteesRegulationAccess(Regulation $regulation): void
    {
        $this->assertCommitteeMember();

        abort_unless(
            $regulation->audience_scope === ParticipantAudienceResolver::SCOPE_COMMITTEE
                && $this->committeeMemberships()->contains($regulation->audience_committee)
                && $regulation->recipients()->where('users.id', auth()->id())->exists(),
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

    private function authorizeContributorMeetingAccess(Meeting $meeting): void
    {
        abort_unless(
            in_array($meeting->audience_scope, $this->mainContributorAudienceScopes(), true)
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

    private function dashboardCharts(Contributor $contributor, array $stats): array
    {
        $ownShares = (float) $stats['shares'];
        $otherShares = max((float) $stats['total_shares'] - $ownShares, 0);
        $sellOffers = $contributor->sellShares()->get(['count', 'ad_status']);
        $purchaseOrders = $contributor->sharesPOs()->get(['count', 'po_status']);
        $independentPurchaseOrders = $contributor->independentPurchaseOrders()->get(['count', 'status']);

        return [
            'ownership' => [
                'labels' => [__('أسهمك'), __('باقي المساهمين')],
                'data' => [$ownShares, $otherShares],
            ],
            'trading' => [
                'labels' => [__('عروض البيع'), __('طلبات الشراء'), __('طلبات الشراء المستقلة')],
                'counts' => [
                    $sellOffers->count(),
                    $purchaseOrders->count(),
                    $independentPurchaseOrders->count(),
                ],
                'shares' => [
                    round((float) $sellOffers->sum('count'), 2),
                    round((float) $purchaseOrders->sum('count'), 2),
                    round((float) $independentPurchaseOrders->sum('count'), 2),
                ],
            ],
        ];
    }

    private function authorizeOwnSellOffer(SellShares $sellShare): void
    {
        abort_unless((int) $sellShare->user_id === (int) $this->contributor()->id, 403);
    }

    private function authorizeOwnIndependentPurchaseOrder(IndependentPurchaseOrder $order): void
    {
        abort_unless((int) $order->contributor_id === (int) $this->contributor()->id, 403);
    }

    private function authorizeOwnPurchaseOrder(SharesPO $sharesPO): void
    {
        abort_unless((int) $sharesPO->user_id === (int) $this->contributor()->id, 403);
    }

    private function authorizeOwnServiceRequest(Booking $booking): void
    {
        abort_unless((int) $booking->user_id === (int) auth()->id(), 403);
    }

    private function assertSellOfferBelongsToIndependentOrder(IndependentPurchaseOrder $order, SellShares $sellShare): void
    {
        abort_unless((int) $sellShare->independent_purchase_order_id === (int) $order->id, 404);
    }

    private function assertPurchaseOrderBelongsToSellOffer(SellShares $sellShare, SharesPO $sharesPO): void
    {
        abort_unless((int) $sharesPO->sale_number === (int) $sellShare->id, 404);
    }

    private function assertIndependentPurchaseOrderEntryAllowed(): void
    {
        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_OFFER) {
            throw ValidationException::withMessages([
                'trading_period' => 'لا يمكن تقديم طلب شراء مستقل إلا خلال الفترة الأولى من التداول.',
            ]);
        }
    }

    private function closeIndependentPurchaseOrder(IndependentPurchaseOrder $order): void
    {
        if (!$order->canBeClosed()) {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن إغلاق الطلب لوجود عروض بيع قيد المعالجة أو عروض مقبولة مرتبطة به.',
            ]);
        }

        $order->update([
            'status' => IndependentPurchaseOrder::STATUS_CLOSED,
            'closed_at' => now(),
        ]);
    }

    private function syncIndependentPurchaseOrderStatus(IndependentPurchaseOrder $order): void
    {
        $order->refresh();

        if ((int) $order->status !== IndependentPurchaseOrder::STATUS_PUBLISHED) {
            return;
        }

        $hasPendingOffers = $order->sellOffers()
            ->where('independent_offer_status', SellShares::INDEPENDENT_STATUS_PENDING)
            ->exists();

        if (!$hasPendingOffers && $order->accepted_shares >= (float) $order->count) {
            $order->update(['status' => IndependentPurchaseOrder::STATUS_COMPLETED]);
        }
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

        if ($sellShare->independent_purchase_order_id && $sellShare->independent_offer_status !== SellShares::INDEPENDENT_STATUS_PENDING) {
            return false;
        }

        return !$sellShare->sharesPOs()->exists();
    }

    private function canSettleOwnSellOffer(SellShares $sellShare): bool
    {
        if (app(TradingWindowService::class)->currentPhase() !== TradingPeriod::PHASE_PROCESSING) {
            return false;
        }

        if (in_array((int) $sellShare->ad_status, [SellShares::AD_STATUS_COMPLETED, SellShares::AD_STATUS_CANCELLED], true)) {
            return false;
        }

        if ($sellShare->independent_purchase_order_id) {
            return false;
        }

        return !$sellShare->settlement?->allocations()
            ->where('transferred_count', '>', 0)
            ->exists();
    }

    private function highestActivePurchaseOrderPrice(SellShares $sellShare): float
    {
        return (float) $sellShare->sharesPOs()
            ->where(function ($query): void {
                $query
                    ->whereNull('po_status')
                    ->orWhereNotIn('po_status', [
                        SharesPO::PO_STATUS_COMPLETED,
                        SharesPO::PO_STATUS_REJECTED,
                    ]);
            })
            ->max('amount_per_share');
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
