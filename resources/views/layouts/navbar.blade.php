<nav
    class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
    id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-2 px-4">

        {{-- Breadcrumb dan page title --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">

                <li class="breadcrumb-item text-sm">
                    <a
                        class="opacity-5 text-white"
                        href="{{ route('web.dashboard') }}">
                        Pages
                    </a>
                </li>
                <li
                    class="breadcrumb-item text-sm text-white active"
                    aria-current="page">
                    @yield('page-title', 'Dashboard')
                </li>

            </ol>

            <h6 class="font-weight-bolder text-white mb-0">
                @yield('page-title', 'Dashboard')
            </h6>
        </nav>


        {{-- Navbar kanan --}}
        <div
            class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0"
            id="navbar">
            <ul class="navbar-nav ms-auto align-items-center">

                {{-- Mobile sidebar toggle --}}
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a
                        href="javascript:;"
                        class="nav-link text-white p-0"
                        id="iconNavbarSidenav"
                        aria-label="Toggle sidebar">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>

                {{-- User dropdown --}}
                @php
                $initials = collect(explode(' ', auth()->user()->name))
                ->take(2)
                ->map(fn ($name) => strtoupper(substr($name, 0, 1)))
                ->implode('');
                @endphp

                <li class="nav-item dropdown d-flex align-items-center ms-3 me-2">
                    <a
                        href="javascript:;"
                        class="nav-link text-white font-weight-bold p-0 d-flex align-items-center"
                        id="userDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="navbar-avatar me-2">
                            {{ $initials }}
                        </div>

                        <div class="d-none d-sm-flex flex-column text-start">
                            <span class="text-sm font-weight-bold text-white lh-1">
                                {{ auth()->user()->name }}
                            </span>

                            <span class="text-xs text-white opacity-8 mt-1">
                                {{ auth()->user()->role->name }}
                            </span>
                        </div>

                        <i class="fas fa-chevron-down text-xs ms-2"></i>
                    </a>

                    <ul
                        class="dropdown-menu dropdown-menu-end px-2 py-3 mt-2"
                        aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-item border-radius-md">
                                <p class="text-sm font-weight-bold mb-1">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="text-xs text-secondary mb-0">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </li>

                        <li>
                            <hr class="horizontal dark my-2">
                        </li>

                        <li>
                            <a
                                href="{{ route('web.profile.edit') }}"
                                class="dropdown-item border-radius-md">
                                <i class="fas fa-user-cog me-2"></i>
                                Profile
                            </a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item border-radius-md text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>
