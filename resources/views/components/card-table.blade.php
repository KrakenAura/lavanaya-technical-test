@props([
'title',
'subtitle' => null,
])

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0">
                    {{ $title }}
                </h6>

                @if ($subtitle)
                <p class="text-sm text-secondary mb-0 mt-1">
                    {{ $subtitle }}
                </p>
                @endif
            </div>

            @if (isset($action))
            <div>
                {{ $action }}
            </div>
            @endif
        </div>
    </div>

    <div class="card-body px-0 pb-2">
        <div class="table-responsive">
            {{ $slot }}
        </div>
    </div>
</div>
