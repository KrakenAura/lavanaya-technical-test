<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        DashboardService $dashboardService
    ): View {
        $user = $request->user();
        $role = $user->role->name;

        $data = match ($role) {
            Role::STAFF =>
            $dashboardService->getStaffDashboard($user),

            Role::SUPERVISOR,
            Role::MANAGER,
            Role::DIRECTOR =>
            $dashboardService->getApprovalDashboard($user),

            Role::FINANCE =>
            $dashboardService->getFinanceDashboard($user),

            default => [],
        };

        return match ($role) {
            Role::STAFF => $this->staffDashboard($data),

            Role::SUPERVISOR,
            Role::MANAGER,
            Role::DIRECTOR => $this->approvalDashboard($data),

            Role::FINANCE => $this->financeDashboard($data),

            default => view('dashboard.index', [
                'stats' => [],
                'recentSubmissions' => collect(),
                'approvalSummary' => [
                    'waiting' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
            ]),
        };
    }


    private function staffDashboard(array $data): View
    {
        $stats = [
            [
                'title' => 'Total Submission',
                'value' => $data['total_submission'],
                'icon' => 'ni ni-single-copy-04',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Draft',
                'value' => $data['draft'],
                'icon' => 'ni ni-ruler-pencil',
                'color' => 'bg-gradient-secondary',
            ],
            [
                'title' => 'In Process',
                'value' => $data['in_process'],
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Paid',
                'value' => $data['paid'],
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
        ];

        $recentSubmissions = $data['recent_submissions'];

        $approvalSummary = [
            'waiting' => $data['approval_summary']['waiting'],
            'approved' => $data['approval_summary']['paid'],
            'rejected' => $data['approval_summary']['rejected'],
        ];

        return view(
            'dashboard.index',
            compact(
                'stats',
                'recentSubmissions',
                'approvalSummary'
            )
        );
    }


    private function approvalDashboard(array $data): View
    {
        $stats = [
            [
                'title' => 'Total Assigned',
                'value' => $data['total_assigned'],
                'icon' => 'ni ni-single-copy-04',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Waiting Approval',
                'value' => $data['waiting_approval'],
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Approved',
                'value' => $data['approved'],
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
            [
                'title' => 'Rejected',
                'value' => $data['rejected'],
                'icon' => 'ni ni-fat-remove',
                'color' => 'bg-gradient-danger',
            ],
        ];

        $recentSubmissions = $data['recent_approvals']
            ->map(function ($approval) {
                return [
                    'number' =>
                    $approval->submission->submission_number,

                    'title' =>
                    $approval->submission->title,

                    'amount' =>
                    'Rp ' . number_format(
                        $approval->submission->amount,
                        0,
                        ',',
                        '.'
                    ),

                    'status' =>
                    $approval->status,
                ];
            });

        $approvalSummary = $data['approval_summary'];

        return view(
            'dashboard.index',
            compact(
                'stats',
                'recentSubmissions',
                'approvalSummary'
            )
        );
    }


    private function financeDashboard(array $data): View
    {
        $stats = [
            [
                'title' => 'Total Assigned',
                'value' => $data['total_assigned'],
                'icon' => 'ni ni-credit-card',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Waiting Payment',
                'value' => $data['waiting_payment'],
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Paid',
                'value' => $data['paid'],
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
            [
                'title' => 'Total Paid',
                'value' => 'Rp ' . number_format(
                    $data['total_paid'],
                    0,
                    ',',
                    '.'
                ),
                'icon' => 'ni ni-money-coins',
                'color' => 'bg-gradient-info',
            ],
        ];

        $recentSubmissions = $data['recent_payments']
            ->map(function ($payment) {
                return [
                    'number' =>
                    $payment->submission->submission_number,

                    'title' =>
                    $payment->submission->title,

                    'amount' =>
                    'Rp ' . number_format(
                        $payment->amount,
                        0,
                        ',',
                        '.'
                    ),

                    'status' =>
                    $payment->status,
                ];
            });

        $approvalSummary = [
            'waiting' =>
            $data['payment_summary']['waiting'],

            'approved' =>
            $data['payment_summary']['paid'],

            'rejected' =>
            $data['payment_summary']['rejected'],
        ];

        return view(
            'dashboard.index',
            compact(
                'stats',
                'recentSubmissions',
                'approvalSummary'
            )
        );
    }
}
