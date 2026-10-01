<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RequestDeliveryStatus;
use App\Services\RequestDeliveryTracker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RequestTrackingAckController extends Controller
{
    public function __invoke(Request $request, RequestDeliveryTracker $deliveryTracker): JsonResponse
    {
        $trackingId = (string) $request->query('tracking_id', '');

        validator(
            ['tracking_id' => $trackingId],
            ['tracking_id' => ['required', 'uuid']]
        )->validate();

        $validated = $request->validate([
            'channel' => ['required', 'string', Rule::in(RequestDeliveryStatus::BROWSER_CHANNELS)],
            'status' => ['required', 'string', Rule::in(RequestDeliveryStatus::BROWSER_STATUSES)],
            'error_code' => ['nullable', 'string', 'max:100'],
            'error_message' => ['nullable', 'string', 'max:500'],
            'http_status' => ['nullable', 'integer', 'min:100', 'max:599'],
            'meta' => ['nullable', 'array'],
        ]);

        try {
            $updated = $deliveryTracker->markBrowserAck(
                $trackingId,
                $validated['channel'],
                $validated['status'],
                [
                    'error_code' => $validated['error_code'] ?? null,
                    'error_message' => $validated['error_message'] ?? null,
                    'http_status' => $validated['http_status'] ?? null,
                    'meta' => $validated['meta'] ?? [],
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Request delivery browser ACK failed', [
                'tracking_id' => $trackingId,
                'channel' => $validated['channel'] ?? null,
                'status' => $validated['status'] ?? null,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to store delivery acknowledgement.',
            ], 503);
        }

        abort_unless($updated, 404);

        return response()->json(['success' => true]);
    }
}
