<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\ContributorMovement;
use App\Models\ShareTransLine;
use App\Models\SharesTrans;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContributorMovementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function (Request $request, $next) {
            abort_unless($request->user()?->isAdmin(), 403, 'هذه الصفحة متاحة لحساب الادمن فقط.');

            return $next($request);
        });
    }

    public function index(): View
    {
        $movements = ContributorMovement::query()
            ->with(['fromContributor', 'toContributor', 'sharesTrans'])
            ->latest('date')
            ->paginate(12);

        return view('contributor-movements.index', compact('movements'));
    }

    public function create(): View
    {
        $contributors = Contributor::query()
            ->orderBy('name')
            ->get(['id', 'name', 'share_count_cr']);

        return view('contributor-movements.create', compact('contributors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'trans_type' => ['required', 'integer', Rule::in([
                SharesTrans::TRANS_TYPE_BUY,
                SharesTrans::TRANS_TYPE_SELL,
                SharesTrans::TRANS_TYPE_TRANSFER,
                SharesTrans::TRANS_TYPE_DIVIDEND,
            ])],
            'from_contributor_id' => ['nullable', 'exists:contributors,id'],
            'to_contributor_id' => ['nullable', 'exists:contributors,id'],
            'shares_count' => ['required', 'numeric', 'gt:0'],
            'amount_per_share' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:500'],
        ]);

        $type = (int) $validated['trans_type'];
        $fromId = $validated['from_contributor_id'] ?? null;
        $toId = $validated['to_contributor_id'] ?? null;
        $sharesCount = round((float) $validated['shares_count'], 2);

        $this->validateMovementParties($request, $type, $fromId, $toId);

        DB::transaction(function () use ($validated, $type, $fromId, $toId, $sharesCount): void {
            $fromContributor = $fromId ? Contributor::query()->lockForUpdate()->findOrFail($fromId) : null;
            $toContributor = $toId ? Contributor::query()->lockForUpdate()->findOrFail($toId) : null;
            $fromBalanceBefore = $fromContributor ? (float) ($fromContributor->share_count_cr ?? 0) : null;
            $toBalanceBefore = $toContributor ? (float) ($toContributor->share_count_cr ?? 0) : null;

            if ($fromContributor && $type !== SharesTrans::TRANS_TYPE_DIVIDEND) {
                $currentShares = $fromBalanceBefore;

                if ($currentShares < $sharesCount) {
                    throw ValidationException::withMessages([
                        'shares_count' => 'رصيد المساهم المحدد في حقل "من" لا يكفي لإتمام الحركة.',
                    ]);
                }

                $fromContributor->update([
                    'share_count_cr' => round($currentShares - $sharesCount, 2),
                ]);
            }

            if ($toContributor && $type !== SharesTrans::TRANS_TYPE_DIVIDEND) {
                $toContributor->update([
                    'share_count_cr' => round((float) ($toContributor->share_count_cr ?? 0) + $sharesCount, 2),
                ]);
            }

            $transaction = SharesTrans::create([
                'date' => $validated['date'],
                'notes' => $validated['description'],
                'trans_type' => $type,
                'posted' => true,
            ]);

            if ($fromContributor && $type !== SharesTrans::TRANS_TYPE_DIVIDEND) {
                ShareTransLine::create([
                    'contributor_id' => $fromContributor->id,
                    'trans_id' => $transaction->id,
                    'count_debit' => $sharesCount,
                    'count_credit' => 0,
                    'amount_per_share' => $validated['amount_per_share'],
                    'line_notes' => $validated['description'],
                    'posted' => true,
                ]);
            }

            if ($toContributor) {
                ShareTransLine::create([
                    'contributor_id' => $toContributor->id,
                    'trans_id' => $transaction->id,
                    'count_debit' => 0,
                    'count_credit' => $type === SharesTrans::TRANS_TYPE_DIVIDEND ? 0 : $sharesCount,
                    'amount_per_share' => $validated['amount_per_share'],
                    'line_notes' => $validated['description'],
                    'posted' => true,
                ]);
            }

            ContributorMovement::create([
                'date' => $validated['date'],
                'movement_type' => $type,
                'from_contributor_id' => $fromContributor?->id,
                'to_contributor_id' => $toContributor?->id,
                'shares_count' => $sharesCount,
                'amount_per_share' => $validated['amount_per_share'],
                'from_balance_before' => $fromBalanceBefore,
                'from_balance_after' => $fromContributor ? (float) $fromContributor->share_count_cr : null,
                'to_balance_before' => $toBalanceBefore,
                'to_balance_after' => $toContributor ? (float) $toContributor->share_count_cr : null,
                'description' => $validated['description'],
                'shares_trans_id' => $transaction->id,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('contributor-movements.index')
            ->with('success', 'تمت إضافة حركة المساهم وتحديث الأرصدة بنجاح.');
    }

    private function validateMovementParties(Request $request, int $type, ?string $fromId, ?string $toId): void
    {
        $errors = [];

        if ($type === SharesTrans::TRANS_TYPE_SELL && !$fromId) {
            $errors['from_contributor_id'] = 'يجب اختيار المساهم في حقل "من" لحركة البيع.';
        }

        if (in_array($type, [SharesTrans::TRANS_TYPE_BUY, SharesTrans::TRANS_TYPE_DIVIDEND], true) && !$toId) {
            $errors['to_contributor_id'] = 'يجب اختيار المساهم في حقل "إلى" لهذا النوع من الحركات.';
        }

        if ($type === SharesTrans::TRANS_TYPE_TRANSFER && (!$fromId || !$toId)) {
            $errors['from_contributor_id'] = 'حركة المناقلة تتطلب اختيار المساهمين "من" و"إلى".';
        }

        if ($fromId && $toId && $fromId === $toId) {
            $errors['to_contributor_id'] = 'لا يمكن اختيار نفس المساهم في حقلي "من" و"إلى".';
        }

        if (!empty($errors)) {
            back()->withErrors($errors)->withInput()->throwResponse();
        }
    }
}
