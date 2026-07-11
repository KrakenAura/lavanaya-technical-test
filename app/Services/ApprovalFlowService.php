<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Submission;

class ApprovalFlowService
{
    public function getApprovalRoles(Submission $submission)
    {

        // PO Product
        if ($submission->is_po) {
            return [
                Role::DIRECTOR,
                Role::FINANCE,
            ];
        }


        // Non PO > 10 juta
        if ($submission->amount > 10000000) {
            return [
                Role::SUPERVISOR,
                Role::MANAGER,
                Role::DIRECTOR,
                Role::FINANCE,
            ];
        }


        // Non PO > 5 juta
        if ($submission->amount > 5000000) {
            return [
                Role::SUPERVISOR,
                Role::MANAGER,
                Role::FINANCE,
            ];
        }


        // Non PO <= 5 juta
        return [
            Role::SUPERVISOR,
            Role::FINANCE,
        ];
    }


    public function getApprover(string $role)
    {
        $approver = Role::where('name', $role)
            ->first()
            ?->users()
            ->first();


        if (!$approver) {
            throw new \Exception(
                "Approver not found for role: {$role}"
            );
        }


        return $approver;
    }
}
