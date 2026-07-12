<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\User;

class DashboardService
{
    public function getStaffDashboard(User $user): array
    {
        $baseQuery = Submission::query()
            ->where('user_id', $user->id);

        $inProcessStatuses = [
            Submission::SUBMITTED,
            Submission::WAITING_SPV_APPROVAL,
            Submission::WAITING_MANAGER_APPROVAL,
            Submission::WAITING_DIRECTOR_APPROVAL,
            Submission::WAITING_FINANCE,
        ];

        return [
            'total_submission' => (clone $baseQuery)->count(),

            'draft' => (clone $baseQuery)
                ->where('status', Submission::DRAFT)
                ->count(),

            'in_process' => (clone $baseQuery)
                ->whereIn('status', $inProcessStatuses)
                ->count(),

            'paid' => (clone $baseQuery)
                ->where('status', Submission::PAID)
                ->count(),

            'rejected' => (clone $baseQuery)
                ->where('status', Submission::REJECTED)
                ->count(),

            'total_paid_amount' => (clone $baseQuery)
                ->where('status', Submission::PAID)
                ->sum('amount'),

            'recent_submissions' => Submission::query()
                ->with('category')
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get(),

            'approval_summary' => [
                'waiting' => (clone $baseQuery)
                    ->whereIn('status', $inProcessStatuses)
                    ->count(),

                'paid' => (clone $baseQuery)
                    ->where('status', Submission::PAID)
                    ->count(),

                'rejected' => (clone $baseQuery)
                    ->where('status', Submission::REJECTED)
                    ->count(),
            ],
        ];
    }


    public function getApprovalDashboard(User $user): array
    {
        $baseQuery = Approval::query()
            ->where('approver_id', $user->id);

        return [
            'total_assigned' => (clone $baseQuery)->count(),

            'waiting_approval' => (clone $baseQuery)
                ->where('status', Approval::WAITING)
                ->count(),

            'approved' => (clone $baseQuery)
                ->where('status', Approval::APPROVED)
                ->count(),

            'rejected' => (clone $baseQuery)
                ->where('status', Approval::REJECTED)
                ->count(),

            'approved_today' => (clone $baseQuery)
                ->where('status', Approval::APPROVED)
                ->whereDate('acted_at', today())
                ->count(),

            'recent_approvals' => Approval::query()
                ->with([
                    'submission.user',
                    'submission.category',
                ])
                ->where('approver_id', $user->id)
                ->latest()
                ->limit(5)
                ->get(),

            'approval_summary' => [
                'waiting' => (clone $baseQuery)
                    ->where('status', Approval::WAITING)
                    ->count(),

                'approved' => (clone $baseQuery)
                    ->where('status', Approval::APPROVED)
                    ->count(),

                'rejected' => (clone $baseQuery)
                    ->where('status', Approval::REJECTED)
                    ->count(),
            ],
        ];
    }


    public function getFinanceDashboard(User $user): array
    {
        $baseQuery = Payment::query()
            ->where('finance_user_id', $user->id);

        return [
            'total_assigned' => (clone $baseQuery)->count(),

            'waiting_payment' => (clone $baseQuery)
                ->where('status', Payment::WAITING)
                ->count(),

            'paid' => (clone $baseQuery)
                ->where('status', Payment::PAID)
                ->count(),

            'rejected' => (clone $baseQuery)
                ->where('status', Payment::REJECTED)
                ->count(),

            'paid_today' => (clone $baseQuery)
                ->where('status', Payment::PAID)
                ->whereDate('paid_at', today())
                ->count(),

            'total_paid' => (clone $baseQuery)
                ->where('status', Payment::PAID)
                ->sum('amount'),

            'recent_payments' => Payment::query()
                ->with([
                    'submission.user',
                    'submission.category',
                ])
                ->where('finance_user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get(),

            'payment_summary' => [
                'waiting' => (clone $baseQuery)
                    ->where('status', Payment::WAITING)
                    ->count(),

                'paid' => (clone $baseQuery)
                    ->where('status', Payment::PAID)
                    ->count(),

                'rejected' => (clone $baseQuery)
                    ->where('status', Payment::REJECTED)
                    ->count(),
            ],
        ];
    }
}
