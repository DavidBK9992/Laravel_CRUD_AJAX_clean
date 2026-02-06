@php
    $badgeClass = $row->post_status === 'active' ? 'dt-status--active' : 'dt-status--inactive';
@endphp
<div class="dt-status-wrapper">
    <span class="dt-status {{ $badgeClass }}">
        <span class="dt-status-dot"></span>
        {{ ucfirst($row->post_status) }}
    </span>
    <button class="dt-status-toggle" data-id="{{ $row->id }}">
        <img src="{{ asset('change.png') }}" class="dt-status-icon">
    </button>
</div>
