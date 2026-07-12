<div
    class="toast-container position-fixed top-0 end-0 p-3"
    style="z-index: 1080;">
    @if (session('success'))
    <div
        class="toast align-items-center text-bg-success border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="3500">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
    @endif


    @if (session('error'))
    <div
        class="toast align-items-center text-bg-danger border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
    @endif


    @if (session('warning'))
    <div
        class="toast align-items-center text-bg-warning border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="4500">
        <div class="d-flex">
            <div class="toast-body text-dark">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
            </div>

            <button
                type="button"
                class="btn-close me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
    @endif


    @if ($errors->any())
    <div
        class="toast align-items-center text-bg-danger border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="6000">
        <div class="d-flex">
            <div class="toast-body">
                <div class="fw-bold mb-1">
                    Please check the form.
                </div>

                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li class="text-sm">
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
    @endif
</div>
