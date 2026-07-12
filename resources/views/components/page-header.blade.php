@props([
'title',
'parent' => 'Pages',
'subtitle' => null,
])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-1 p-0">

                <li class="breadcrumb-item text-sm text-white opacity-8">
                    {{ $parent }}
                </li>

                <li
                    class="breadcrumb-item text-sm text-white active"
                    aria-current="page">
                    {{ $title }}
                </li>

            </ol>
        </nav>

        <h4 class="font-weight-bolder text-white mb-0">
            {{ $title }}
        </h4>

        @if ($subtitle)
        <p class="text-sm text-white opacity-8 mb-0 mt-1">
            {{ $subtitle }}
        </p>
        @endif

    </div>

    @isset($action)
    <div class="mt-3 mt-md-0">
        {{ $action }}
    </div>
    @endisset
</div>
