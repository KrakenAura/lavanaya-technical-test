@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="dashboard-welcome mb-4">
    <p class="text-white text-sm mb-1 opacity-8">
        Welcome back,
    </p>

    <h4 class="text-white font-weight-bolder mb-0">
        {{ auth()->user()->name }}
    </h4>
</div>

<div class="row g-4 mb-4">
    @foreach ($stats as $stat)
    <div class="col-xl-3 col-md-6">
        <x-dashboard.stat-card
            :title="$stat['title']"
            :value="$stat['value']"
            :icon="$stat['icon']"
            :color="$stat['color']"
            :description="$stat['description'] ?? null"
            :description-color="$stat['description_color'] ?? 'text-success'" />
    </div>
    @endforeach
</div>

<div class="row g-4">

    <div class="col-xl-8">
        <x-card-table
            title="Recent Submissions"
            subtitle="Latest expense submission activity">
            <x-slot:action>
                <a
                    href="#"
                    class="btn btn-sm btn-outline-primary mb-0">
                    View All
                </a>
            </x-slot:action>

            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">
                            Number
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Title
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Amount
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentSubmissions as $submission)
                    <tr>
                        <td class="ps-4">
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $submission['number'] }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm mb-0">
                                {{ $submission['title'] }}
                            </p>
                        </td>

                        <td>
                            <p class="text-sm font-weight-bold mb-0">
                                {{ $submission['amount'] }}
                            </p>
                        </td>

                        <td>
                            <x-status-badge
                                :status="$submission['status']" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="4"
                            class="text-center py-5 text-secondary">
                            No submission data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card-table>
    </div>


    {{-- Approval summary --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6 class="mb-0">
                    Approval Summary
                </h6>

                <p class="text-sm text-secondary mb-0 mt-1">
                    Current approval activity
                </p>
            </div>

            <div class="card-body pt-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-warning shadow text-center me-3">
                            <i class="fas fa-clock text-white opacity-10"></i>
                        </div>

                        <span class="text-sm font-weight-bold">
                            Waiting
                        </span>
                    </div>

                    <span class="badge bg-gradient-warning">
                        {{ $approvalSummary['waiting'] }}
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center me-3">
                            <i class="fas fa-check text-white opacity-10"></i>
                        </div>

                        <span class="text-sm font-weight-bold">
                            Approved
                        </span>
                    </div>

                    <span class="badge bg-gradient-success">
                        {{ $approvalSummary['approved'] }}
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-danger shadow text-center me-3">
                            <i class="fas fa-times text-white opacity-10"></i>
                        </div>

                        <span class="text-sm font-weight-bold">
                            Rejected
                        </span>
                    </div>

                    <span class="badge bg-gradient-danger">
                        {{ $approvalSummary['rejected'] }}
                    </span>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
