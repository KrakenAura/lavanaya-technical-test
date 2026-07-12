<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function index(
    //     Request $request,
    //     DashboardService $dashboardService
    // ) {

    //     $user = $request->user();

    //     $role = $user->role->name;


    //     $data = match ($role) {

    //         'Staff' =>
    //         $dashboardService->getStaffDashboard($user),

    //         'Supervisor',
    //         'Manager',
    //         'Director' =>
    //         $dashboardService->getApprovalDashboard($user),

    //         'Finance' =>
    //         $dashboardService->getFinanceDashboard($user),

    //         default => []
    //     };


    //     return view(
    //         'dashboard.index',
    //         compact('data')
    //     );
    // }
    public function index()
    {

        $stats = [
            [
                'title' => 'Total Submission',
                'value' => 24,
                'icon' => 'ni ni-single-copy-04',
                'color' => 'bg-gradient-primary',
            ],

            [
                'title' => 'Pending Approval',
                'value' => 8,
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],

            [
                'title' => 'Approved',
                'value' => 12,
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],

            [
                'title' => 'Total Expense',
                'value' => 'Rp 25.000.000',
                'icon' => 'ni ni-money-coins',
                'color' => 'bg-gradient-info',
            ],
        ];


        $recentSubmissions = [
            [
                'number' => 'SUB-001',
                'title' => 'Laptop Purchase',
                'amount' => 'Rp 15.000.000',
                'status' => 'Waiting Approval',
            ],

            [
                'number' => 'SUB-002',
                'title' => 'Office Supplies',
                'amount' => 'Rp 2.500.000',
                'status' => 'Approved',
            ],

            [
                'number' => 'SUB-003',
                'title' => 'Travel Expense',
                'amount' => 'Rp 1.200.000',
                'status' => 'Paid',
            ],
        ];


        $approvalSummary = [
            'waiting' => 5,
            'approved' => 15,
            'rejected' => 2,
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
