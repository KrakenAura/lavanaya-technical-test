<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApprovalController extends Controller
{
    /**
     * Approve an expense submission.
     *
     * The authenticated user must be assigned as the approver
     * and the approval status must be waiting.
     */
    public function approve(Approval $approval): JsonResponse
    {
        $this->authorize('approve', $approval);


        $approval->update([
            'status' => Approval::APPROVED,
            'acted_at' => now(),
        ]);


        $approval->submission->update([
            'status' => Submission::APPROVED,
        ]);


        return response()->json([
            'message' => 'Submission approved successfully',
            'data' => $approval->load([
                'submission',
                'approver',
            ]),
        ]);
    }


    /**
     * Reject an expense submission.
     *
     * The authenticated user must be assigned as the approver
     * and the approval status must be waiting.
     */
    public function reject(
        Request $request,
        Approval $approval
    ): JsonResponse {

        $this->authorize('reject', $approval);


        $approval->update([
            'status' => Approval::REJECTED,
            'notes' => $request->notes,
            'acted_at' => now(),
        ]);


        $approval->submission->update([
            'status' => Submission::REJECTED,
        ]);


        return response()->json([
            'message' => 'Submission rejected successfully',
            'data' => $approval->load([
                'submission',
                'approver',
            ]),
        ]);
    }
}
