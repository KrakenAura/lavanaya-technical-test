@extends('layouts.app')

@section('page-title', 'Approval Queue')

@section('content')

{{-- Page header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Approval
        </p>

        <h4 class="text-white font-weight-bolder mb-0">
            Approval Queue
        </h4>
    </div>
</div>


{{-- Quick statistics --}}
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


{{-- Approval table --}}
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h6 class="mb-0">
                    Assigned Approvals
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Review expense submissions assigned to you.
                </p>
            </div>
        </div>
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
                            Category
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Amount
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Level
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Status
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($approvals as $approval)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="text-sm font-weight-bold text-dark">
                                    {{ $approval->submission->submission_number }}
                                </span>

                                <span class="text-xs text-secondary">
                                    {{ $approval->submission->title }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $approval->submission->user->name }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                {{ $approval->submission->user->email }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm mb-0">
                                {{ $approval->submission->category?->name ?? '-' }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                Rp {{ number_format(
                                            $approval->submission->amount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                            </p>
                        </td>

                        <td>
                            <span class="badge bg-gradient-info">
                                Level {{ $approval->level }}
                            </span>
                        </td>

                        <td>
                            <x-status-badge :status="$approval->status" />
                        </td>

                        <td class="align-middle text-center">
                            <div class="dropdown position-static">
                                <button
                                    type="button"
                                    class="table-action table-action-dropdown"
                                    id="approvalAction{{ $approval->id }}"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    aria-label="Approval actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul
                                    class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="approvalActionDropdown{{ $approval->id }}">
                                    <li>
                                        <a
                                            href="{{ route('web.approvals.show', $approval) }}"
                                            class="dropdown-item">
                                            <i class="fa-solid fa-eye me-2"></i>
                                            View Detail
                                        </a>
                                    </li>

                                    @if ($approval->status === \App\Models\Approval::WAITING)
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <span class="dropdown-item-text text-xs text-secondary">
                                            Decision available in detail page
                                        </span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="7"
                            class="text-center py-5">
                            <i class="fa-solid fa-clipboard-check text-secondary fs-3 mb-3"></i>

                            <h6 class="mb-1">
                                No approval assignments
                            </h6>

                            <p class="text-sm text-secondary mb-0">
                                There are no submissions waiting for your review.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($approvals->hasPages())
    <div class="card-footer py-3">
        {{ $approvals->links() }}
    </div>
    @endif
</div>

@endsection
