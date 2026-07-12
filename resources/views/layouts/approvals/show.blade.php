@extends('layouts.app')

@section('page-title', 'Approval Detail')

@section('content')

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Approval Detail
        </p>

        <div class="d-flex align-items-center gap-2">
            <h4 class="text-white font-weight-bolder mb-0">
                {{ $approval->submission->submission_number }}
            </h4>

            <x-status-badge :status="$approval->status" />
        </div>
    </div>

    <a
        href="{{ route('web.approvals.index') }}"
        class="btn btn-outline-light mb-0 mt-3 mt-md-0">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Back
    </a>
</div>


<div class="row g-4">

    {{-- Main submission detail --}}
    <div class="col-xl-8">

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Submission Information
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Expense request submitted for your review.
                </p>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Submission Number
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $approval->submission->submission_number }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Requester
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $approval->submission->user->name }}
                        </p>

                        <p class="text-xs text-secondary mb-0">
                            {{ $approval->submission->user->email }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Title
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $approval->submission->title }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Category
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $approval->submission->category?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Amount
                        </p>

                        <p class="text-lg font-weight-bolder mb-0">
                            Rp {{ number_format(
                                    $approval->submission->amount,
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
                            {{ $approval->submission->is_po ? 'Yes' : 'No' }}
                        </p>
                    </div>

                    <div class="col-12">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Description
                        </p>

                        <p class="text-sm mb-0">
                            {{ $approval->submission->description ?: '-' }}
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
                    Supporting documents uploaded by requester.
                </p>
            </div>

            <div class="card-body">
                @forelse ($approval->submission->attachments as $attachment)

                <div class="d-flex justify-content-between align-items-center bg-gray-100 border-radius-lg p-3 mb-2">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center me-3">
                            <i class="fa-solid fa-paperclip text-white"></i>
                        </div>

                        <div>
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $attachment->file_name }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                Supporting document
                            </p>
                        </div>
                    </div>

                    <a
                        href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-primary mb-0">
                        <i class="fa-solid fa-eye me-2"></i>
                        View
                    </a>
                </div>

                @empty

                <div class="text-center py-4">
                    <i class="fa-solid fa-paperclip text-secondary mb-2"></i>

                    <p class="text-sm text-secondary mb-0">
                        No attachment available.
                    </p>
                </div>

                @endforelse
            </div>
        </div>

    </div>


    {{-- Approval sidebar --}}
    <div class="col-xl-4">

        {{-- Decision card --}}
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Approval Decision
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Level {{ $approval->level }}
                    —
                    {{ $approval->approver?->role?->name ?? 'Approver' }}
                </p>
            </div>

            <div class="card-body">
                @if ($approval->status === \App\Models\Approval::WAITING)

                <p class="text-sm text-secondary">
                    Review the submission information and supporting
                    documents before making a decision.
                </p>

                <form
                    method="POST"
                    action="{{ route('web.approvals.approve', $approval) }}"
                    onsubmit="return confirm('Approve this submission?')">
                    @csrf

                    <button
                        type="submit"
                        class="btn bg-gradient-success w-100 mb-2">
                        <i class="fa-solid fa-check me-2"></i>
                        Approve
                    </button>
                </form>

                <button
                    type="button"
                    class="btn btn-outline-danger w-100 mb-0"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectApprovalModal">
                    <i class="fa-solid fa-xmark me-2"></i>
                    Reject
                </button>

                @else

                <div class="text-center py-3">
                    <x-status-badge :status="$approval->status" />

                    <p class="text-sm text-secondary mt-3 mb-0">
                        This approval decision has already been completed.
                    </p>

                    @if ($approval->notes)
                    <div class="bg-gray-100 border-radius-lg p-3 mt-3 text-start">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Notes
                        </p>

                        <p class="text-sm mb-0">
                            {{ $approval->notes }}
                        </p>
                    </div>
                    @endif
                </div>

                @endif
            </div>
        </div>


        {{-- Approval timeline --}}
        <div class="card">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Approval Timeline
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Complete submission approval history.
                </p>
            </div>

            <div class="card-body">

                @foreach (
                $approval->submission->approvals->sortBy('level')
                as $timelineApproval
                )
                <div class="d-flex mb-4">
                    <div class="me-3">
                        @if ($timelineApproval->status === \App\Models\Approval::APPROVED)
                        <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center rounded-circle">
                            <i class="fa-solid fa-check text-white"></i>
                        </div>
                        @elseif ($timelineApproval->status === \App\Models\Approval::REJECTED)
                        <div class="icon icon-shape icon-sm bg-gradient-danger shadow text-center rounded-circle">
                            <i class="fa-solid fa-xmark text-white"></i>
                        </div>
                        @else
                        <div class="icon icon-shape icon-sm bg-gradient-warning shadow text-center rounded-circle">
                            <i class="fa-solid fa-clock text-white"></i>
                        </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-weight-bold mb-1">
                            Level {{ $timelineApproval->level }}
                            —
                            {{ $timelineApproval->approver?->role?->name ?? 'Approver' }}
                        </p>

                        <p class="text-xs text-secondary mb-1">
                            {{ $timelineApproval->approver?->name ?? '-' }}
                        </p>

                        <x-status-badge :status="$timelineApproval->status" />

                        @if ($timelineApproval->acted_at)
                        <p class="text-xs text-secondary mt-1 mb-0">
                            {{ $timelineApproval->acted_at->format('d M Y H:i') }}
                        </p>
                        @endif

                        @if ($timelineApproval->notes)
                        <p class="text-xs mt-2 mb-0">
                            {{ $timelineApproval->notes }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>

    </div>

</div>



@endsection

@section('modals')

@if ($approval->status === \App\Models\Approval::WAITING)
<div
    class="modal fade"
    id="rejectApprovalModal"
    tabindex="-1"
    aria-labelledby="rejectApprovalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('web.approvals.reject', $approval) }}">
                @csrf

                <div class="modal-header">
                    <h5
                        class="modal-title"
                        id="rejectApprovalModalLabel">
                        Reject Submission
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label for="notes" class="form-label">
                        Rejection Reason
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Explain why this submission is rejected..."
                        required>{{ old('notes') }}</textarea>

                    @error('notes')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-outline-secondary mb-0"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn bg-gradient-danger mb-0">
                        Reject Submission
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if ($errors->has('notes'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById(
            'rejectApprovalModal'
        );

        if (modalElement) {
            bootstrap.Modal
                .getOrCreateInstance(modalElement)
                .show();
        }
    });
</script>
@endif
@endpush
