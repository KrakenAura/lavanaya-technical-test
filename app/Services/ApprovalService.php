<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\Role;
use App\Models\Submission;

class ApprovalService
{
    public function createInitialApproval(
        Submission $submission
    ): Approval {
        $supervisor = Role::where(
            'name',
            Role::SUPERVISOR
        )
            ->first()
            ->users()
            ->first();


        return Approval::create([
            'submission_id' => $submission->id,
            'approver_id' => $supervisor->id,
            'level' => 1,
            'status' => Approval::WAITING,
        ]);
    }


    public function approve(
        Approval $approval
    ): void {
        $approval->update([
            'status' => Approval::APPROVED,
            'acted_at' => now(),
        ]);


        $approval->submission->update([
            'status' => Submission::APPROVED,
        ]);
    }


    public function reject(
        Approval $approval,
        ?string $notes = null
    ): void {
        $approval->update([
            'status' => Approval::REJECTED,
            'notes' => $notes,
            'acted_at' => now(),
        ]);


        $approval->submission->update([
            'status' => Submission::REJECTED,
        ]);
    }
}
