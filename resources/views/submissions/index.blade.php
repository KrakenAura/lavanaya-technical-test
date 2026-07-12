@extends('layouts.app')

@section('page-title', 'My Submissions')

@section('content')

{{-- Page header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Submission
        </p>

        <h4 class="text-white font-weight-bolder mb-0">
            My Submissions
        </h4>
    </div>

    <div class="mt-3 mt-md-0">
        <a
            href="{{ route('web.submissions.create') }}"
            class="btn bg-gradient-primary mb-0">
            <i class="fas fa-plus me-2"></i>
            Create Submission
        </a>
    </div>
</div>


<!-- Quick Stats -->
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


<!-- Submission Table -->
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h6 class="mb-0">
                    Submission List
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Manage your expense submissions
                </p>
            </div>

            <div class="d-flex gap-2">
                <input
                    type="search"
                    class="form-control form-control-sm"
                    placeholder="Search submission..."
                    disabled>

                <select
                    class="form-select form-select-sm"
                    disabled>
                    <option>
                        All statuses
                    </option>
                </select>
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
                            Category
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Amount
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Status
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Date
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                            Action
                        </th>

                    </tr>
                </thead>

                <tbody>
                    @forelse ($submissions as $submission)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="text-sm font-weight-bold text-dark">
                                    {{ $submission->submission_number }}
                                </span>

                                <span class="text-xs text-secondary">
                                    {{ $submission->title }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <p class="text-sm mb-0">
                                {{ $submission->category?->name ?? '-' }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                Rp {{ number_format($submission->amount, 0, ',', '.') }}
                            </p>
                        </td>

                        <td>
                            <x-status-badge :status="$submission->status" />
                        </td>

                        <td>
                            <p class="text-sm mb-0">
                                {{ $submission->created_at->format('d M Y') }}
                            </p>

                            <p class="text-xs text-secondary mb-0">
                                {{ $submission->created_at->format('H:i') }}
                            </p>
                        </td>

                        <td class="align-middle text-center pe-4">
                            <div class="dropdown">
                                <button
                                    type="button"
                                    class="btn btn-link text-secondary p-0 m-0 shadow-none"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fs-6"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a
                                            href="{{ route('web.submissions.show', $submission) }}"
                                            class="dropdown-item">
                                            <i class="fas fa-eye me-2"></i>
                                            View Detail
                                        </a>
                                    </li>

                                    @if ($submission->status === \App\Models\Submission::DRAFT)
                                    <li>
                                        <a
                                            href="{{ route('web.submissions.edit', $submission) }}"
                                            class="dropdown-item">
                                            <i class="fas fa-edit me-2"></i>
                                            Edit
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <form
                                            method="POST"
                                            action="{{ route('web.submissions.destroy', $submission) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="dropdown-item text-danger">
                                                <i class="fa-solid fa-trash me-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <h6 class="mb-1">
                                No submissions yet
                            </h6>

                            <p class="text-sm text-secondary mb-3">
                                Create your first expense submission.
                            </p>

                            <a
                                href="{{ route('web.submissions.create') }}"
                                class="btn btn-sm bg-gradient-primary mb-0">
                                Create Submission
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($submissions->hasPages())
    <div class="card-footer py-3">
        {{ $submissions->links() }}
    </div>
    @endif
</div>

@endsection
