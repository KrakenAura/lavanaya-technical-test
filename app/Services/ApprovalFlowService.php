<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Submission;
use InvalidArgumentException;
use RuntimeException;

class ApprovalFlowService
{
    public function getApprovalRoles(
        Submission $submission
    ): array {
        /*
         * PO Produk:
         * Staff → Director → Finance
         *
         * Finance bukan approver.
         */
        if ($submission->is_po) {
            return [
                Role::DIRECTOR,
            ];
        }

        /*
         * Non-PO > Rp10 juta:
         * Staff → SPV → Manager → Director → Finance
         */
        if ($submission->amount > 10_000_000) {
            return [
                Role::SUPERVISOR,
                Role::MANAGER,
                Role::DIRECTOR,
            ];
        }

        /*
         * Non-PO > Rp5 juta:
         * Staff → SPV → Manager → Finance
         */
        if ($submission->amount > 5_000_000) {
            return [
                Role::SUPERVISOR,
                Role::MANAGER,
            ];
        }

        /*
         * Non-PO <= Rp5 juta:
         * Staff → SPV → Finance
         */
        return [
            Role::SUPERVISOR,
        ];
    }


    public function getApprover(string $role): User
    {
        $approver = Role::query()
            ->where('name', $role)
            ->first()
            ?->users()
            ->first();

        if (!$approver) {
            throw new RuntimeException(
                "Approver not found for role: {$role}"
            );
        }

        return $approver;
    }


    public function getWaitingStatus(
        string $role
    ): string {
        return match ($role) {
            Role::SUPERVISOR =>
            Submission::WAITING_SPV_APPROVAL,

            Role::MANAGER =>
            Submission::WAITING_MANAGER_APPROVAL,

            Role::DIRECTOR =>
            Submission::WAITING_DIRECTOR_APPROVAL,

            default => throw new InvalidArgumentException(
                "Unsupported approval role: {$role}"
            ),
        };
    }
}
