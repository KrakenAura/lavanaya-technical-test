@php
$user = auth()->user();
$role = $user->role->name;

$isStaff = $role === \App\Models\Role::STAFF;

$isApprover = in_array($role, [
\App\Models\Role::SUPERVISOR,
\App\Models\Role::MANAGER,
\App\Models\Role::DIRECTOR,
]);

$isFinance = $role === \App\Models\Role::FINANCE;
@endphp

<aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i
            class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true"
            id="iconSidenav"></i>

        <a
            class="navbar-brand m-0"
            href="{{ route('web.dashboard') }}">
            <img
                src="{{ asset('assets/argon/img/logo-ct-dark.png') }}"
                width="26px"
                height="26px"
                class="navbar-brand-img h-100"
                alt="Lavanaya logo">

            <span class="ms-1 font-weight-bold">
                Lavanaya Madinah Travel
            </span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div
        class="collapse navbar-collapse w-auto"
        id="sidenav-collapse-main">
        <ul class="navbar-nav">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('web.dashboard') ? 'active' : '' }}"
                    href="{{ route('web.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        Dashboard
                    </span>
                </a>
            </li>


            {{-- Staff --}}
            @if ($isStaff)
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                    Submission
                </h6>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('web.submissions.index', 'web.submissions.show') ? 'active' : '' }}"
                    href="{{ route('web.submissions.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        My Submissions
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('web.submissions.create') ? 'active' : '' }}"
                    href="{{ route('web.submissions.create') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-fat-add text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        Create Submission
                    </span>
                </a>
            </li>
            @endif


            {{-- Supervisor, Manager, Director --}}
            @if ($isApprover)
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                    Approval
                </h6>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('web.approvals.*') ? 'active' : '' }}"
                    href="{{ route('web.approvals.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-check-bold text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        Approval Queue
                    </span>
                </a>
            </li>
            @endif


            {{-- Finance --}}
            @if ($isFinance)
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                    Payment
                </h6>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('web.payments.*') ? 'active' : '' }}"
                    href="{{ route('web.payments.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        Payment Queue
                    </span>
                </a>
            </li>
            @endif


            {{-- Account --}}
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                    Account
                </h6>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                    href="{{ route('web.profile.edit') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>

                    <span class="nav-link-text ms-1">
                        Profile
                    </span>
                </a>
            </li>

        </ul>
    </div>
</aside>
