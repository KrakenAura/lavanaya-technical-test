<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Approval;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    public function __construct(
        protected ApprovalFlowService $flowService,
        protected PaymentService $paymentService
    ) {}


    public function createInitialApproval(
        Submission $submission
    ): Approval {
        return DB::transaction(function () use ($submission) {
            $workflow = $this->flowService
                ->getApprovalRoles($submission);

            $firstRole = $workflow[0];

            $approver = $this->flowService
                ->getApprover($firstRole);

            /*
             * Ubah status dari submitted menjadi status
             * approver pertama.
             */
            $submission->update([
                'status' => $this->flowService
                    ->getWaitingStatus($firstRole),
            ]);

            return Approval::create([
                'submission_id' => $submission->id,
                'approver_id' => $approver->id,
                'level' => 1,
                'status' => Approval::WAITING,
            ]);
        });
    }


    public function approve(
        Approval $approval
    ): void {
        DB::transaction(function () use ($approval) {
            $approval->loadMissing([
                'submission',
            ]);

            /*
             * Cegah approval diproses dua kali.
             */
            if ($approval->status !== Approval::WAITING) {
                throw new \RuntimeException(
                    'Only waiting approval can be processed.'
                );
            }

            $approval->update([
                'status' => Approval::APPROVED,
                'acted_at' => now(),
            ]);

            $submission = $approval->submission;

            $workflow = $this->flowService
                ->getApprovalRoles($submission);

            $nextLevel = $approval->level + 1;

            /*
             * Masih ada approver berikutnya.
             */
            if (isset($workflow[$nextLevel - 1])) {
                $nextRole = $workflow[$nextLevel - 1];

                $nextApprover = $this->flowService
                    ->getApprover($nextRole);

                Approval::create([
                    'submission_id' => $submission->id,
                    'approver_id' => $nextApprover->id,
                    'level' => $nextLevel,
                    'status' => Approval::WAITING,
                ]);

                $submission->update([
                    'status' => $this->flowService
                        ->getWaitingStatus($nextRole),
                ]);

                return;
            }

            /*
             * Seluruh approval selesai.
             */
            $submission->update([
                'status' => Submission::WAITING_FINANCE,
            ]);

            /*
             * Finance bukan approver.
             * Finance menerima payment queue.
             */
            $finance = $this->flowService
                ->getApprover(Role::FINANCE);

            if (!$submission->payment()->exists()) {
                $this->paymentService->createPayment(
                    $submission,
                    $finance
                );
            }
        });
    }


    public function reject(
        Approval $approval,
        ?string $notes = null
    ): void {
        DB::transaction(function () use (
            $approval,
            $notes
        ) {
            if ($approval->status !== Approval::WAITING) {
                throw new \RuntimeException(
                    'Only waiting approval can be rejected.'
                );
            }

            $approval->update([
                'status' => Approval::REJECTED,
                'notes' => $notes,
                'acted_at' => now(),
            ]);

            $approval->submission->update([
                'status' => Submission::REJECTED,
            ]);
        });
    }
}
