@php
    $channels = [
        \App\Models\RequestDeliveryStatus::CHANNEL_BITRIX => 'BITRIX24',
        \App\Models\RequestDeliveryStatus::CHANNEL_UIS => 'UIS',
        \App\Models\RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA => 'ЯНДЕКС МЕТРИКА',
    ];
@endphp

<div class="card">
    <div class="card-body">
        <h5 class="card-header align-items-center d-flex">Доставка и аналитика</h5>
        <div class="table-responsive">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr>
                        <th class="ps-0" scope="row">Tracking ID:</th>
                        <td class="text-muted">{{ $item->tracking_id ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0" scope="row">Metrika ClientID:</th>
                        <td class="text-muted">{{ $item->metrika_client_id ?: '— Не получен' }}</td>
                    </tr>
                    @foreach ($channels as $channel => $label)
                        @php
                            $deliveryStatus = $item->deliveryStatusFor($channel);
                            $meta = $deliveryStatus?->meta ?? [];
                            $time = $deliveryStatus?->confirmed_at
                                ?? $deliveryStatus?->sent_at
                                ?? $deliveryStatus?->failed_at
                                ?? $deliveryStatus?->updated_at;
                        @endphp
                        <tr>
                            <th class="ps-0 align-top" scope="row">{{ $label }}:</th>
                            <td class="text-muted">
                                @if ($deliveryStatus)
                                    <div>
                                        <span class="badge {{ $deliveryStatus->badgeClass() }}">{{ $deliveryStatus->statusLabel() }}</span>
                                    </div>
                                    @if ($time)
                                        <div class="small mt-1">Время: {{ $time }}</div>
                                    @endif
                                    @if ($deliveryStatus->external_id)
                                        <div class="small mt-1">Lead ID: {{ $deliveryStatus->external_id }}</div>
                                    @endif
                                    @if ($deliveryStatus->http_status)
                                        <div class="small mt-1">HTTP: {{ $deliveryStatus->http_status }}</div>
                                    @endif
                                    @if (!empty($meta['goal']))
                                        <div class="small mt-1">Goal: {{ $meta['goal'] }}</div>
                                    @endif
                                    @if (!empty($meta['form_id']))
                                        <div class="small mt-1">Form ID: {{ $meta['form_id'] }}</div>
                                    @endif
                                    @if ($deliveryStatus->error_code)
                                        <div class="small mt-1">Код: {{ $deliveryStatus->error_code }}</div>
                                    @endif
                                    @if ($deliveryStatus->error_message)
                                        <div class="small mt-1">Причина: {{ $deliveryStatus->error_message }}</div>
                                    @endif
                                @else
                                    — Нет данных
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
