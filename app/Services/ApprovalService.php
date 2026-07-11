<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    public function __construct(
        protected ApprovalFlowService $flowService
    ) {}


    public function createInitialApproval(Submission $submission){
        $workflow = $this->flowService
            ->getApprovalRoles($submission);


        $firstRole = $workflow[0];


        $approver = $this->flowService
            ->getApprover($firstRole);


        return Approval::create([
            'submission_id' => $submission->id,
            'approver_id' => $approver->id,
            'level' => 1,
            'status' => Approval::WAITING,
        ]);
    }


    public function approve(Approval $approval){
        DB::transaction(function () use ($approval){

            $approval->update([
                'status' => Approval::APPROVED,
                'acted_at' => now(),
            ]);


            $submission = $approval->submission;

            $workflow = $this->flowService
                ->getApprovalRoles($submission);


            $nextLevel = $approval->level + 1;


            if (isset($workflow[$nextLevel - 1])) {

                $nextRole = $workflow[$nextLevel - 1];

                $approver = $this->flowService
                    ->getApprover($nextRole);


                Approval::create([
                    'submission_id' => $submission->id,
                    'approver_id' => $approver->id,
                    'level' => $nextLevel,
                    'status' => Approval::WAITING,
                ]);

                return;
            }


            $submission->update([
                'status' => Submission::WAITING_FINANCE,
            ]);
        });
    }


    public function reject(Approval $approval,?string $notes = null){
        $approval->update([
            'status' => Approval::REJECTED,
            'notes' => $notes,
            'acted_at' => now(),
        ]);


        $approval->submission->update
        ([
            'status' => Submission::REJECTED,
        ]);
    }
}
