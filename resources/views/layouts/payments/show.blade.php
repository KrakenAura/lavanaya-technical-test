@extends('layouts.app')

@section('page-title', 'Payment Detail')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Payment Detail
        </p>

        <div class="d-flex align-items-center gap-2">
            <h4 class="text-white font-weight-bolder mb-0">
                {{ $payment->submission->submission_number }}
            </h4>

            <x-status-badge :status="$payment->status" />
        </div>
    </div>

    <a
        href="{{ route('web.payments.index') }}"
        class="btn btn-outline-light mb-0 mt-3 mt-md-0">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Back
    </a>
</div>

<div class="row g-4">

    <div class="col-xl-8">

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Submission Information
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Approved expense request ready for Finance processing.
                </p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Requester
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $payment->submission->user->name }}
                        </p>

                        <p class="text-xs text-secondary mb-0">
                            {{ $payment->submission->user->email }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Category
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $payment->submission->category?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Title
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $payment->submission->title }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Amount
                        </p>

                        <p class="text-lg font-weight-bolder mb-0">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Purchase Order
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $payment->submission->is_po ? 'Yes' : 'No' }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-4">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Submitted At
                        </p>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $payment->submission->submitted_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="col-12">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Description
                        </p>

                        <p class="text-sm mb-0">
                            {{ $payment->submission->description ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Attachments
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Supporting documents uploaded by requester.
                </p>
            </div>

            <div class="card-body">
                @forelse ($payment->submission->attachments as $attachment)
                <div class="d-flex justify-content-between align-items-center bg-gray-100 border-radius-lg p-3 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-paperclip text-primary me-3"></i>

                        <p class="text-sm font-weight-bold mb-0">
                            {{ $attachment->file_name }}
                        </p>
                    </div>

                    <a
                        href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-primary mb-0">
                        View
                    </a>
                </div>
                @empty
                <p class="text-sm text-secondary mb-0">
                    No attachment available.
                </p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Approval History
                </h6>
            </div>

            <div class="card-body">
                @foreach ($payment->submission->approvals->sortBy('level') as $approval)
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center rounded-circle">
                            <i class="fa-solid fa-check text-white"></i>
                        </div>
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

                        <x-status-badge :status="$approval->status" />

                        @if ($approval->acted_at)
                        <p class="text-xs text-secondary mt-1 mb-0">
                            {{ $approval->acted_at->format('d M Y H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="col-xl-4">

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Budget Validation
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Validate the available category budget.
                </p>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Period
                    </span>

                    <span class="text-sm font-weight-bold">
                        {{ $budget->period }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Allocated Budget
                    </span>

                    <span class="text-sm font-weight-bold">
                        Rp {{ number_format($budget->amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Used Budget
                    </span>

                    <span class="text-sm font-weight-bold">
                        Rp {{ number_format($budget->used_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Remaining Budget
                    </span>

                    <span class="text-sm font-weight-bold">
                        Rp {{ number_format($remainingBudget, 0, ',', '.') }}
                    </span>
                </div>

                <hr class="horizontal dark">

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-sm text-secondary">
                        Payment Amount
                    </span>

                    <span class="text-sm font-weight-bold">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between">
                    <span class="text-sm text-secondary">
                        Remaining After Payment
                    </span>

                    <span
                        class="text-sm font-weight-bold {{ $remainingAfterPayment < 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format(
                            $remainingAfterPayment,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Finance Action
                </h6>
            </div>

            <div class="card-body">
                @if ($payment->status === \App\Models\Payment::WAITING)

                @if ($remainingAfterPayment >= 0)
                <form
                    method="POST"
                    action="{{ route('web.payments.process', $payment) }}"
                    onsubmit="return confirm('Process this payment?')">
                    @csrf

                    <button
                        type="submit"
                        class="btn bg-gradient-success w-100 mb-2">
                        <i class="fa-solid fa-check me-2"></i>
                        Process Payment
                    </button>
                </form>
                @else
                <div class="alert alert-danger text-white text-sm">
                    Category budget is insufficient.
                </div>
                @endif

                <button
                    type="button"
                    class="btn btn-outline-danger w-100 mb-0"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectPaymentModal">
                    <i class="fa-solid fa-xmark me-2"></i>
                    Reject Payment
                </button>

                @else
                <div class="text-center py-3">
                    <x-status-badge :status="$payment->status" />

                    <p class="text-sm text-secondary mt-3 mb-0">
                        This payment has already been processed.
                    </p>

                    @if ($payment->rejection_notes)
                    <div class="bg-gray-100 border-radius-lg p-3 mt-3 text-start">
                        <p class="text-xs text-uppercase text-secondary font-weight-bolder mb-1">
                            Rejection Notes
                        </p>

                        <p class="text-sm mb-0">
                            {{ $payment->rejection_notes }}
                        </p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection

@section('modals')

@if ($payment->status === \App\Models\Payment::WAITING)
<div
    class="modal fade"
    id="rejectPaymentModal"
    tabindex="-1"
    aria-labelledby="rejectPaymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('web.payments.reject', $payment) }}">
                @csrf

                <div class="modal-header">
                    <h5
                        class="modal-title"
                        id="rejectPaymentModalLabel">
                        Reject Payment
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label
                        for="rejection_notes"
                        class="form-label">
                        Rejection Notes
                    </label>

                    <textarea
                        id="rejection_notes"
                        name="rejection_notes"
                        rows="4"
                        class="form-control @error('rejection_notes') is-invalid @enderror"
                        placeholder="Explain why this payment is rejected...">{{ old('rejection_notes') }}</textarea>

                    @error('rejection_notes')
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
                        Reject Payment
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif

@endsection