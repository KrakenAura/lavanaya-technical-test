@extends('layouts.app')

@section('page-title', 'Submission Detail')

@section('content')

{{-- Page header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Submission
        </p>

        <div class="d-flex align-items-center gap-2">
            <h4 class="text-white font-weight-bolder mb-0">
                {{ $submission->submission_number }}
            </h4>

            <x-status-badge :status="$submission->status" />
        </div>
    </div>

    <div class="d-flex gap-2 mt-3 mt-md-0">
        <a
            href="{{ route('web.submissions.index') }}"
            class="btn btn-outline-light mb-0">
            <i class="fas fa-arrow-left me-2"></i>
            Back
        </a>

        @if ($submission->status === \App\Models\Submission::DRAFT)
        <a
            href="{{ route('web.submissions.edit', $submission) }}"
            class="btn bg-gradient-info mb-0">
            <i class="fas fa-edit me-2"></i>
            Edit
        </a>
        @endif
    </div>
</div>


<div class="row g-4">

    {{-- Main submission information --}}
    <div class="col-xl-8">

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Submission Information
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Expense request details
                </p>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Submission Number
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $submission->submission_number }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Requester
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $submission->user->name }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Title
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $submission->title }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Category
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $submission->category?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Amount
                        </p>

                        <p class="text-lg font-weight-bolder mb-0">
                            Rp {{ number_format(
                                    $submission->amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Purchase Order
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $submission->is_po ? 'Yes' : 'No' }}
                        </p>
                    </div>

                    <div class="col-12">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Description
                        </p>

                        <p class="text-sm mb-0">
                            {{ $submission->description ?: '-' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>


        {{-- Attachments --}}
        <div class="card">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Attachments
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Supporting expense documents
                </p>
            </div>

            <div class="card-body">
                @forelse ($submission->attachments as $attachment)

                <div class="d-flex justify-content-between align-items-center border-radius-lg bg-gray-100 p-3 mb-2">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center me-3">
                            <i class="fas fa-paperclip text-white"></i>
                        </div>

                        <div>
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $attachment->file_name }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                Uploaded document
                            </p>
                        </div>
                    </div>

                    <a
                        href="{{ Storage::url($attachment->file_path) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-primary mb-0">
                        <i class="fas fa-eye me-2"></i>
                        View
                    </a>
                </div>

                @empty

                <div class="text-center py-4">
                    <i class="fas fa-paperclip text-secondary mb-2"></i>

                    <p class="text-sm text-secondary mb-0">
                        No attachment uploaded.
                    </p>
                </div>

                @endforelse
            </div>
        </div>

    </div>


    {{-- Right side --}}
    <div class="col-xl-4">

        {{-- Actions --}}
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Submission Action
                </h6>
            </div>

            <div class="card-body">
                @if ($submission->status === \App\Models\Submission::DRAFT)

                <p class="text-sm text-secondary">
                    Review the information before sending this
                    submission to the approval process.
                </p>

                <form
                    method="POST"
                    action="{{ route(
                                'web.submissions.submit',
                                $submission
                            ) }}"
                    onsubmit="return confirm(
                                'Submit this expense request for approval?'
                            )">
                    @csrf

                    <button
                        type="submit"
                        class="btn bg-gradient-primary w-100 mb-2">
                        <i class="fas fa-paper-plane me-2"></i>
                        Submit for Approval
                    </button>
                </form>

                <a
                    href="{{ route(
                                'web.submissions.edit',
                                $submission
                            ) }}"
                    class="btn btn-outline-secondary w-100 mb-0">
                    Edit Submission
                </a>

                @else

                <div class="text-center py-3">
                    <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle mx-auto mb-3">
                        <i class="fas fa-check text-white"></i>
                    </div>

                    <h6 class="mb-1">
                        Submission Sent
                    </h6>

                    <p class="text-sm text-secondary mb-0">
                        This submission has entered the approval process.
                    </p>
                </div>

                @endif
            </div>
        </div>


        {{-- Approval timeline --}}
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Approval Timeline
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Current approval progress
                </p>
            </div>

            <div class="card-body">

                @forelse (
                $submission->approvals
                ->sortBy('level')
                as $approval
                )

                <div class="d-flex mb-4">
                    <div class="me-3">
                        @if ($approval->status === \App\Models\Approval::APPROVED)
                        <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center rounded-circle">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        @elseif ($approval->status === \App\Models\Approval::REJECTED)
                        <div class="icon icon-shape icon-sm bg-gradient-danger shadow text-center rounded-circle">
                            <i class="fas fa-times text-white"></i>
                        </div>
                        @else
                        <div class="icon icon-shape icon-sm bg-gradient-warning shadow text-center rounded-circle">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-weight-bold mb-1">
                            Level {{ $approval->level }}
                            —
                            {{ $approval->approver?->role?->name ?? 'Approver' }}
                        </p>

                        <p class="text-xs text-secondary mb-1">
                            {{ $approval->approver?->name ?? '-' }}
                        </p>

                        <x-status-badge
                            :status="$approval->status" />

                        @if ($approval->acted_at)
                        <p class="text-xs text-secondary mt-1 mb-0">
                            {{ $approval->acted_at->format(
                                            'd M Y H:i'
                                        ) }}
                        </p>
                        @endif

                        @if ($approval->notes)
                        <p class="text-xs mt-2 mb-0">
                            {{ $approval->notes }}
                        </p>
                        @endif
                    </div>
                </div>

                @empty

                <div class="text-center py-4">
                    <i class="fas fa-clock text-secondary mb-2"></i>

                    <p class="text-sm text-secondary mb-0">
                        Approval process has not started.
                    </p>
                </div>

                @endforelse

            </div>
        </div>


        {{-- Payment --}}
        @if ($submission->payment)
        <div class="card">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Payment Information
                </h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Status
                    </span>

                    <x-status-badge
                        :status="$submission->payment->status" />
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Amount
                    </span>

                    <span class="text-sm font-weight-bold">
                        Rp {{ number_format(
                                    $submission->payment->amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                    </span>
                </div>

                @if ($submission->payment->paid_at)
                <div class="d-flex justify-content-between">
                    <span class="text-sm text-secondary">
                        Paid At
                    </span>

                    <span class="text-sm font-weight-bold">
                        {{ $submission
                                        ->payment
                                        ->paid_at
                                        ->format('d M Y H:i') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

</div>

@endsection
