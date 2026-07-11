<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Approval;
use App\Models\Payment;
use App\Models\User;

class DashboardService
{
    public function getStaffDashboard(User $user){
        return [
            'total_submission' => Submission::where(
                'user_id',
                $user->id
            )->count(),

            'pending' => Submission::where(
                'user_id',
                $user->id
            )
                ->where(
                    'status',
                    Submission::SUBMITTED
                )
                ->count(),

            'approved' => Submission::where(
                'user_id',
                $user->id
            )
                ->where(
                    'status',
                    Submission::APPROVED
                )
                ->count(),

            'total_amount' => Submission::where(
                'user_id',
                $user->id
            )
                ->sum('amount'),
        ];
    }


    public function getApprovalDashboard(User $user){
        return [
            'waiting_approval' => Approval::where(
                'approver_id',
                $user->id
            )
                ->where(
                    'status',
                    Approval::WAITING
                )
                ->count(),

            'approved' => Approval::where(
                'approver_id',
                $user->id
            )
                ->where(
                    'status',
                    Approval::APPROVED
                )
                ->count(),

            'rejected' => Approval::where(
                'approver_id',
                $user->id
            )
                ->where(
                    'status',
                    Approval::REJECTED
                )
                ->count(),
        ];
    }


    public function getFinanceDashboard(User $user){
        return [
            'waiting_payment' => Payment::where(
                'finance_user_id',
                $user->id
            )
                ->where(
                    'status',
                    Payment::WAITING
                )
                ->count(),

            'paid' => Payment::where(
                'finance_user_id',
                $user->id
            )
                ->where(
                    'status',
                    Payment::PAID
                )
                ->count(),

            'total_paid' => Payment::where(
                'finance_user_id',
                $user->id
            )
                ->where(
                    'status',
                    Payment::PAID
                )
                ->sum('amount'),
        ];
    }
}
