@extends('layouts.app')

@section('page-title', 'Payment Queue')

@section('content')

<div class="mb-4">
    <p class="text-white text-sm mb-1 opacity-8">
        Payment
    </p>

    <h4 class="text-white font-weight-bolder mb-0">
        Payment Queue
    </h4>
</div>

<div class="row g-4 mb-4">
    @foreach ($stats as $stat)
    <div class="col-xl-3 col-md-6">
        <x-dashboard.stat-card
            :title="$stat['title']"
            :value="$stat['value']"
            :icon="$stat['icon']"
            :color="$stat['color']" />
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header pb-0">
        <h6 class="mb-0">
            Assigned Payments
        </h6>

        <p class="text-sm text-secondary mb-0 mt-1">
            Process approved expense submissions.
        </p>
    </div>

    <div class="card-body px-0 pb-2">
        <div class="table-responsive submission-table-wrapper">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">
                            Submission
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Requester
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Amount
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Status
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Assigned At
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($payments as $payment)
                    <tr>
                        <td class="ps-4">
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $payment->submission->submission_number }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                {{ $payment->submission->title }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $payment->submission->user->name }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                Rp {{ number_format(
                                        $payment->amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                            </p>
                        </td>

                        <td>
                            <x-status-badge :status="$payment->status" />
                        </td>

                        <td>
                            <p class="text-sm mb-0">
                                {{ $payment->created_at->format('d M Y') }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                {{ $payment->created_at->format('H:i') }}
                            </p>
                        </td>

                        <td class="align-middle text-center">
                            <a
                                href="{{ route('web.payments.show', $payment) }}"
                                class="table-action"
                                title="View payment detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fa-solid fa-wallet text-secondary fs-3 mb-3"></i>

                            <h6 class="mb-1">
                                No payment assignments
                            </h6>

                            <p class="text-sm text-secondary mb-0">
                                There are no approved submissions waiting for payment.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($payments->hasPages())
    <div class="card-footer py-3">
        {{ $payments->links() }}
    </div>
    @endif
</div>

@endsection