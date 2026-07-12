<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\BudgetService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $payments = Payment::query()
            ->with([
                'submission.user',
                'submission.category',
            ])
            ->where('finance_user_id', $userId)
            ->latest()
            ->paginate(10);

        $stats = [
            [
                'title' => 'Total Assigned',
                'value' => Payment::where('finance_user_id', $userId)->count(),
                'icon' => 'ni ni-credit-card',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Waiting',
                'value' => Payment::where('finance_user_id', $userId)
                    ->where('status', Payment::WAITING)
                    ->count(),
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Paid',
                'value' => Payment::where('finance_user_id', $userId)
                    ->where('status', Payment::PAID)
                    ->count(),
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
            [
                'title' => 'Total Paid',
                'value' => 'Rp ' . number_format(
                    Payment::where('finance_user_id', $userId)
                        ->where('status', Payment::PAID)
                        ->sum('amount'),
                    0,
                    ',',
                    '.'
                ),
                'icon' => 'ni ni-money-coins',
                'color' => 'bg-gradient-info',
            ],
        ];

        return view(
            'layouts.payments.index',
            compact('payments', 'stats')
        );
    }

    public function show(
        Request $request,
        Payment $payment,
        BudgetService $budgetService
    ): View {
        abort_unless(
            $payment->finance_user_id === $request->user()->id,
            403
        );

        $payment->load([
            'finance.role',
            'submission.user',
            'submission.category',
            'submission.attachments',
            'submission.approvals.approver.role',
        ]);

        $budget = $budgetService->getBudgetForSubmission(
            $payment->submission
        );

        $remainingBudget = $budgetService->remaining($budget);

        $remainingAfterPayment =
            $remainingBudget - (float) $payment->amount;

        return view(
            'layouts.payments.show',
            compact(
                'payment',
                'budget',
                'remainingBudget',
                'remainingAfterPayment'
            )
        );
    }

    public function process(
        Request $request,
        Payment $payment,
        PaymentService $paymentService
    ): RedirectResponse {
        abort_unless(
            $payment->finance_user_id === $request->user()->id,
            403
        );

        try {
            $paymentService->process($payment);

            return redirect()
                ->route('web.payments.index')
                ->with(
                    'success',
                    'Payment processed successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $exception->getMessage()
            );
        }
    }

    public function reject(
        Request $request,
        Payment $payment,
        PaymentService $paymentService
    ): RedirectResponse {
        abort_unless(
            $payment->finance_user_id === $request->user()->id,
            403
        );

        $validated = $request->validate([
            'rejection_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            $paymentService->reject(
                $payment,
                $validated['rejection_notes'] ?? null
            );

            return redirect()
                ->route('web.payments.index')
                ->with(
                    'success',
                    'Payment rejected successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Payment could not be rejected.'
            );
        }
    }
}
