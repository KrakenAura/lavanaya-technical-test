<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request,DashboardService $service){
        $user = $request->user();

        $role = $user->role->name;


        $data = match ($role) {

            'Staff' =>
            $service->getStaffDashboard($user),

            'Supervisor',
            'Manager',
            'Director' =>
            $service->getApprovalDashboard($user),

            'Finance' =>
            $service->getFinanceDashboard($user),

            default =>
            [],
        };


        return response()->json([
            'data' => $data,
        ]);
    }
}
