@php
$normalizedStatus = strtolower($status);

$badgeClass = match ($normalizedStatus) {
'draft' => 'bg-gradient-secondary',

'submitted' => 'bg-gradient-info',

'waiting_spv_approval',
'waiting_manager_approval',
'waiting_director_approval' =>
'bg-gradient-warning',

'waiting_finance' =>
'bg-gradient-primary',

'waiting' =>
'bg-gradient-warning',

'approved',
'paid' =>
'bg-gradient-success',

'rejected' =>
'bg-gradient-danger',

default =>
'bg-gradient-secondary',
};

$label = match ($normalizedStatus) {
'waiting_spv_approval' =>
'Waiting SPV Approval',

'waiting_manager_approval' =>
'Waiting Manager Approval',

'waiting_director_approval' =>
'Waiting Director Approval',

'waiting_finance' =>
'Waiting Finance',

default => str($status)
->replace('_', ' ')
->title(),
};
@endphp

<span class="badge badge-sm {{ $badgeClass }}">
    {{ $label }}
</span>
