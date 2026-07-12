<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentService
{
    public function __construct(
        protected BudgetService $budgetService
    ) {}


    public function createPayment(
        Submission $submission,
        User $finance
    ): Payment {
        return Payment::firstOrCreate(
            [
                'submission_id' => $submission->id,
            ],
            [
                'finance_user_id' => $finance->id,
                'amount' => $submission->amount,
                'status' => Payment::WAITING,
            ]
        );
    }


    public function process(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->loadMissing('submission');

            if ($payment->status !== Payment::WAITING) {
                throw new RuntimeException(
                    'Only waiting payments can be processed.'
                );
            }

            $submission = $payment->submission;

            if (
                $submission->status
                !== Submission::WAITING_FINANCE
            ) {
                throw new RuntimeException(
                    'Submission is not waiting for Finance.'
                );
            }

            $budget = $this->budgetService
                ->getBudgetForSubmission($submission);

            $budget = $budget->newQuery()
                ->whereKey($budget->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                !$this->budgetService->isSufficient(
                    $budget,
                    (float) $payment->amount
                )
            ) {
                throw new RuntimeException(
                    'Category budget is insufficient.'
                );
            }

            $this->budgetService->consume(
                $budget,
                (float) $payment->amount
            );

            $payment->update([
                'status' => Payment::PAID,
                'paid_at' => now(),
                'rejection_notes' => null,
            ]);

            $submission->update([
                'status' => Submission::PAID,
            ]);
        });
    }


    public function reject(
        Payment $payment,
        ?string $notes = null
    ): void {
        DB::transaction(function () use (
            $payment,
            $notes
        ) {
            $payment->loadMissing('submission');

            if ($payment->status !== Payment::WAITING) {
                throw new RuntimeException(
                    'Only waiting payments can be rejected.'
                );
            }

            $payment->update([
                'status' => Payment::REJECTED,
                'rejection_notes' => $notes,
            ]);

            $payment->submission->update([
                'status' => Submission::REJECTED,
            ]);
        });
    }
}
