<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ApprovalService;

class ApprovalController extends Controller
{
    /**
     * Approve an expense submission.
     *
     * The authenticated user must be assigned as the approver
     * and the approval status must be waiting.
     */
    public function approve(Approval $approval,ApprovalService $approvalService)
    {
        $this->authorize('approve', $approval);


        $approvalService->approve($approval);


        return response()->json([
            'message' => 'Submission approved successfully',
            'data' => $approval->fresh(),
        ]);
    }


    /**
     * Reject an expense submission.
     *
     * The authenticated user must be assigned as the approver
     * and the approval status must be waiting.
     */
    public function reject(Request $request,Approval $approval,ApprovalService $approvalService)
    {
        $this->authorize('reject', $approval);


        $approvalService->reject(
            $approval,
            $request->notes
        );


        return response()->json([
            'message' => 'Submission rejected successfully',
            'data' => $approval->fresh(),
        ]);
    }
}
