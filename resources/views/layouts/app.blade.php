<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>

<body class="g-sidenav-show bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg min-vh-100 d-flex flex-column">

        {{-- Background --}}
        <div class="dashboard-background"></div>

        {{-- Navbar --}}
        @include('layouts.navbar')

        {{-- Page content --}}
        <div class="container-fluid dashboard-content flex-grow-1 py-4">
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('layouts.footer')

    </main>
    @yield('modals')

    <x-toast />

    @include('layouts.scripts')
    @stack('scripts')

</body>

</html>
