@php
    $deliveryStatus = $item->deliveryStatusFor($channel);
@endphp

@if ($deliveryStatus)
    <span class="badge {{ $deliveryStatus->badgeClass() }}"
          title="{{ trim(($deliveryStatus->error_code ? $deliveryStatus->error_code . ': ' : '') . ($deliveryStatus->error_message ?? '')) }}">
        {{ $deliveryStatus->statusLabel() }}
    </span>
@else
    <span class="text-muted">— Нет данных</span>
@endif
