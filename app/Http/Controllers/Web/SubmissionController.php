<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\ApprovalService;


class SubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $submissions = Submission::query()
            ->with('category')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $stats = [
            [
                'title' => 'Total Submission',
                'value' => Submission::where('user_id', $userId)->count(),
                'icon' => 'ni ni-single-copy-04',
                'color' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Draft',
                'value' => Submission::where('user_id', $userId)
                    ->where('status', Submission::DRAFT)
                    ->count(),
                'icon' => 'ni ni-ruler-pencil',
                'color' => 'bg-gradient-secondary',
            ],
            [
                'title' => 'In Process',
                'value' => Submission::where('user_id', $userId)
                    ->whereNotIn('status', [
                        Submission::DRAFT,
                        Submission::REJECTED,
                        Submission::PAID,
                    ])
                    ->count(),
                'icon' => 'ni ni-time-alarm',
                'color' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Paid',
                'value' => Submission::where('user_id', $userId)
                    ->where('status', Submission::PAID)
                    ->count(),
                'icon' => 'ni ni-check-bold',
                'color' => 'bg-gradient-success',
            ],
        ];

        return view(
            'submissions.index',
            compact('submissions', 'stats')
        );
    }


    public function create(): View
    {
        $categories = ExpenseCategory::query()
            ->orderBy('name')
            ->get();

        return view(
            'submissions.create',
            compact('categories')
        );
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'is_po' => [
                'nullable',
                'boolean',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ]);


        DB::transaction(function () use (
            $request,
            $validated
        ) {
            $submission = Submission::create([
                'user_id' => $request->user()->id,
                'category_id' => $validated['category_id'],
                'submission_number' => $this
                    ->generateSubmissionNumber(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'amount' => $validated['amount'],
                'is_po' => $request->boolean('is_po'),
                'status' => Submission::DRAFT,
            ]);


            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');

                $path = $file->store(
                    "submissions/{$submission->id}",
                    'public'
                );


                $submission->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        });


        return redirect()
            ->route('web.submissions.index')
            ->with(
                'success',
                'Submission saved as draft successfully.'
            );
    }


    private function generateSubmissionNumber(): string
    {
        return 'EXP-' . now()->format('YmdHis');
    }

    public function show(
        Request $request,
        Submission $submission
    ): View {

        abort_unless(
            $submission->user_id === $request->user()->id,
            403
        );

        $submission->load([
            'user',
            'category',
            'attachments',
            'approvals.approver.role',
            'payment.finance',
        ]);

        return view(
            'submissions.show',
            compact('submission')
        );
    }
    public function submit(
        Request $request,
        Submission $submission,
        ApprovalService $approvalService
    ): RedirectResponse {

        abort_unless(
            $submission->user_id === $request->user()->id,
            403
        );

        if ($submission->status !== Submission::DRAFT) {
            return back()->with(
                'error',
                'Only draft submissions can be submitted.'
            );
        }

        try {
            DB::transaction(function () use (
                $submission,
                $approvalService
            ) {
                $submission->update([
                    'status' => Submission::SUBMITTED,
                    'submitted_at' => now(),
                ]);

                $approvalService
                    ->createInitialApproval($submission);
            });

            return redirect()
                ->route(
                    'web.submissions.show',
                    $submission
                )
                ->with(
                    'success',
                    'Submission sent for approval successfully.'
                );
        } catch (\Throwable $exception) {

            report($exception);

            return back()->with(
                'error',
                'Submission could not be sent for approval.'
            );
        }
    }
    public function edit(
        Request $request,
        Submission $submission
    ): View {
        abort_unless(
            $submission->user_id === $request->user()->id,
            403
        );

        if ($submission->status !== Submission::DRAFT) {
            abort(
                403,
                'Only draft submissions can be edited.'
            );
        }

        $submission->load([
            'category',
            'attachments',
        ]);

        $categories = ExpenseCategory::query()
            ->orderBy('name')
            ->get();

        return view(
            'submissions.edit',
            compact(
                'submission',
                'categories'
            )
        );
    }
    public function update(
        Request $request,
        Submission $submission
    ): RedirectResponse {
        abort_unless(
            $submission->user_id === $request->user()->id,
            403
        );

        if ($submission->status !== Submission::DRAFT) {
            return redirect()
                ->route(
                    'web.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Only draft submissions can be edited.'
                );
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'is_po' => [
                'nullable',
                'boolean',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $submission
            ) {
                $submission->update([
                    'category_id' => $validated['category_id'],
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'amount' => $validated['amount'],
                    'is_po' => $request->boolean('is_po'),
                ]);

                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');

                    $path = $file->store(
                        "submissions/{$submission->id}",
                        'public'
                    );

                    $submission->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            });

            return redirect()
                ->route(
                    'web.submissions.show',
                    $submission
                )
                ->with(
                    'success',
                    'Submission updated successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Submission could not be updated.'
                );
        }
    }
    public function destroy(
        Request $request,
        Submission $submission
    ): RedirectResponse {
        abort_unless(
            $submission->user_id === $request->user()->id,
            403
        );

        if ($submission->status !== Submission::DRAFT) {
            return back()->with(
                'error',
                'Only draft submissions can be deleted.'
            );
        }

        try {
            DB::transaction(function () use ($submission) {

                $submission->load('attachments');

                foreach ($submission->attachments as $attachment) {
                    if (
                        $attachment->file_path &&
                        Storage::disk('public')->exists(
                            $attachment->file_path
                        )
                    ) {
                        Storage::disk('public')->delete(
                            $attachment->file_path
                        );
                    }
                }

                $submission->delete();
            });

            return redirect()
                ->route('web.submissions.index')
                ->with(
                    'success',
                    'Submission deleted successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Submission could not be deleted.'
            );
        }
    }
}
