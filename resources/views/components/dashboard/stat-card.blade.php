@props([
'title',
'value',
'icon' => 'ni ni-chart-bar-32',
'color' => 'bg-gradient-primary',
'description' => null,
'descriptionColor' => 'text-success',
])

<div class="card h-100">
    <div class="card-body p-3">
        <div class="row">
            <div class="col-8">
                <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                        {{ $title }}
                    </p>

                    <h5 class="font-weight-bolder mb-0">
                        {{ $value }}
                    </h5>

                    @if ($description)
                    <p class="mb-0 mt-2 text-sm">
                        <span class="{{ $descriptionColor }} font-weight-bolder">
                            {{ $description }}
                        </span>
                    </p>
                    @endif
                </div>
            </div>

            <div class="col-4 text-end">
                <div
                    class="icon icon-shape {{ $color }} shadow text-center rounded-circle">
                    <i
                        class="{{ $icon }} text-lg opacity-10"
                        aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</div>
