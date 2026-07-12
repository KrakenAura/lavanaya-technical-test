<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $approvals = Approval::query()
            ->with([
                'submission.user',
                'submission.category',
            ])
            ->where('approver_id', $userId)
            ->latest()
            ->paginate(10);

        $stats = [
            [
                'title' => 'Total Assigned',
                'value' => Approval::where('approver_id', $userId)->count(),
                'icon' => 'ni ni-single-copy-04',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Waiting',
                'value' => Approval::where('approver_id', $userId)
                    ->where('status', Approval::WAITING)
                    ->count(),
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Approved',
                'value' => Approval::where('approver_id', $userId)
                    ->where('status', Approval::APPROVED)
                    ->count(),
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
            [
                'title' => 'Rejected',
                'value' => Approval::where('approver_id', $userId)
                    ->where('status', Approval::REJECTED)
                    ->count(),
                'icon' => 'ni ni-fat-remove',
                'color' => 'bg-gradient-danger',
            ],
        ];

        return view(
            'layouts.approvals.index',
            compact('approvals', 'stats')
        );
    }


    public function show(
        Request $request,
        Approval $approval
    ): View {
        abort_unless(
            $approval->approver_id === $request->user()->id,
            403
        );

        $approval->load([
            'submission.user',
            'submission.category',
            'submission.attachments',
            'submission.approvals.approver.role',
            'submission.payment.finance',
            'approver.role',
        ]);

        return view(
            'layouts.approvals.show',
            compact('approval')
        );
    }


    public function approve(
        Request $request,
        Approval $approval,
        ApprovalService $approvalService
    ): RedirectResponse {
        abort_unless(
            $approval->approver_id === $request->user()->id,
            403
        );

        if ($approval->status !== Approval::WAITING) {
            return back()->with(
                'error',
                'Only waiting approvals can be approved.'
            );
        }

        try {
            $approvalService->approve($approval);

            return redirect()
                ->route('web.approvals.index')
                ->with(
                    'success',
                    'Submission approved successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Approval could not be processed.'
            );
        }
    }


    public function reject(
        Request $request,
        Approval $approval,
        ApprovalService $approvalService
    ): RedirectResponse {
        abort_unless(
            $approval->approver_id === $request->user()->id,
            403
        );

        if ($approval->status !== Approval::WAITING) {
            return back()->with(
                'error',
                'Only waiting approvals can be rejected.'
            );
        }

        $validated = $request->validate([
            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        try {
            $approvalService->reject(
                $approval,
                $validated['notes']
            );

            return redirect()
                ->route('web.approvals.index')
                ->with(
                    'success',
                    'Submission rejected successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Rejection could not be processed.'
            );
        }
    }
}
