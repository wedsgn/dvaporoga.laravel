<?php

namespace Tests\Feature;

use App\Models\RequestConsultation;
use App\Models\RequestDeliveryStatus;
use App\Models\RequestProduct;
use App\Models\User;
use App\Services\RequestDeliveryTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class RequestDeliveryMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.bitrix24.metrika_client_id_field' => null]);
    }

    public function test_consultation_success_generates_tracking_and_bitrix_sent_status(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 12345], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload());

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['tracking_id', 'tracking' => ['ack_url']]);

        $request = RequestConsultation::firstOrFail();

        $this->assertNotNull($request->tracking_id);
        $this->assertSame($request->tracking_id, $response->json('tracking_id'));
        $this->assertCount(3, $request->deliveryStatuses);

        $this->assertDatabaseHas('request_delivery_statuses', [
            'request_type' => RequestDeliveryStatus::REQUEST_CONSULTATION,
            'request_id' => $request->id,
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'external_id' => '12345',
            'http_status' => 200,
        ]);
    }

    public function test_product_success_generates_tracking_and_bitrix_sent_status(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 222], 200)]);

        $response = $this->postJson(route('request_product.store'), $this->productPayload());

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['tracking_id', 'tracking' => ['ack_url']]);

        $request = RequestProduct::firstOrFail();

        $this->assertNotNull($request->tracking_id);
        $this->assertCount(3, $request->deliveryStatuses);

        $this->assertDatabaseHas('request_delivery_statuses', [
            'request_type' => RequestDeliveryStatus::REQUEST_PRODUCT,
            'request_id' => $request->id,
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'external_id' => '222',
        ]);
    }

    public function test_car_page_success_uses_consultation_tracking_statuses(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 333], 200)]);

        $carId = $this->createCar();

        $response = $this->postJson(route('requests.car'), [
            'name' => 'Car Client',
            'phone' => '+7 (999) 000-00-03',
            'form_id' => 'car-page-form',
            'car_id' => $carId,
            'policy' => '1',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['tracking_id', 'tracking' => ['ack_url']]);

        $request = RequestConsultation::firstOrFail();

        $this->assertNotNull($request->tracking_id);
        $this->assertCount(3, $request->deliveryStatuses);

        $this->assertDatabaseHas('request_delivery_statuses', [
            'request_type' => RequestDeliveryStatus::REQUEST_CONSULTATION,
            'request_id' => $request->id,
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'external_id' => '333',
        ]);
    }

    public function test_consultation_stores_metrika_client_id_when_present(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1001], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-30',
            'metrika_client_id' => '1234567890.123456',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000030',
            'metrika_client_id' => '1234567890.123456',
        ]);
    }

    public function test_product_stores_metrika_client_id_when_present(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1002], 200)]);

        $response = $this->postJson(route('request_product.store'), $this->productPayload([
            'phone' => '+7 (999) 000-00-31',
            'metrika_client_id' => 'client-123_abc.456',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_products', [
            'phone' => '+79990000031',
            'metrika_client_id' => 'client-123_abc.456',
        ]);
    }

    public function test_car_page_consultation_stores_metrika_client_id_when_present(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1003], 200)]);

        $carId = $this->createCar();

        $response = $this->postJson(route('requests.car'), [
            'name' => 'Car Client',
            'phone' => '+7 (999) 000-00-32',
            'form_id' => 'car-page-form',
            'car_id' => $carId,
            'policy' => '1',
            'metrika_client_id' => 'car-client:123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000032',
            'metrika_client_id' => 'car-client:123',
        ]);
    }

    public function test_missing_metrika_client_id_keeps_main_request_successful_and_null(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1004], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-33',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000033',
            'metrika_client_id' => null,
        ]);
    }

    public function test_invalid_metrika_client_id_array_keeps_main_request_successful_and_null(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1005], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-34',
            'metrika_client_id' => ['bad' => 'value'],
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000034',
            'metrika_client_id' => null,
        ]);
    }

    public function test_oversized_metrika_client_id_keeps_main_request_successful_and_null(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1006], 200)]);

        $response = $this->postJson(route('request_product.store'), $this->productPayload([
            'phone' => '+7 (999) 000-00-35',
            'metrika_client_id' => str_repeat('1', 101),
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_products', [
            'phone' => '+79990000035',
            'metrika_client_id' => null,
        ]);
    }

    public function test_empty_bitrix_metrika_field_config_skips_client_id_payload_field(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1007], 200)]);
        config(['services.bitrix24.metrika_client_id_field' => '']);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-36',
            'metrika_client_id' => '1234567890',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertSingleBitrixLeadPayload(function (array $fields): void {
            $this->assertNoBitrixCustomFields($fields);
            $this->assertSame('+79990000036', $fields['PHONE'][0]['VALUE']);
        });
    }

    public function test_valid_bitrix_metrika_field_config_adds_client_id_to_existing_lead_request(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1008], 200)]);
        config(['services.bitrix24.metrika_client_id_field' => ' UF_CRM_123456 ']);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-37',
            'metrika_client_id' => '1234567890',
            'utm_source' => 'direct',
            'utm_medium' => 'organic',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertSingleBitrixLeadPayload(function (array $fields): void {
            $this->assertSame('1234567890', $fields['UF_CRM_123456']);
            $this->assertSame('+79990000037', $fields['PHONE'][0]['VALUE']);
            $this->assertSame('direct', $fields['UTM_SOURCE']);
            $this->assertSame('organic', $fields['UTM_MEDIUM']);
            $this->assertArrayHasKey('TITLE', $fields);
            $this->assertArrayHasKey('SOURCE_DESCRIPTION', $fields);
            $this->assertStringContainsString('Форма: modal-form-header', $fields['SOURCE_DESCRIPTION']);
        });
    }

    public function test_valid_bitrix_metrika_field_config_skips_payload_field_without_client_id(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1009], 200)]);
        config(['services.bitrix24.metrika_client_id_field' => 'UF_CRM_123456']);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-38',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertSingleBitrixLeadPayload(function (array $fields): void {
            $this->assertNoBitrixCustomFields($fields);
            $this->assertSame('+79990000038', $fields['PHONE'][0]['VALUE']);
        });
    }

    public function test_invalid_bitrix_metrika_field_config_preserves_lead_creation(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1010], 200)]);
        config(['services.bitrix24.metrika_client_id_field' => 'CRM_BAD_FIELD']);

        $response = $this->postJson(route('request_product.store'), $this->productPayload([
            'phone' => '+7 (999) 000-00-39',
            'metrika_client_id' => '1234567890',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $request = RequestProduct::firstOrFail();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'request_type' => RequestDeliveryStatus::REQUEST_PRODUCT,
            'request_id' => $request->id,
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'external_id' => '1010',
        ]);

        $this->assertSingleBitrixLeadPayload(function (array $fields): void {
            $this->assertArrayNotHasKey('CRM_BAD_FIELD', $fields);
            $this->assertNoBitrixCustomFields($fields);
            $this->assertSame('+79990000039', $fields['PHONE'][0]['VALUE']);
        });
    }

    public function test_ordinary_bitrix_lead_without_client_id_keeps_existing_payload_fields(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 1011], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-40',
            'utm_campaign' => 'brand',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertSingleBitrixLeadPayload(function (array $fields): void {
            $this->assertNoBitrixCustomFields($fields);
            $this->assertSame('+79990000040', $fields['PHONE'][0]['VALUE']);
            $this->assertSame('brand', $fields['UTM_CAMPAIGN']);
            $this->assertSame(config('services.bitrix24.source_id') ?: '26', $fields['SOURCE_ID']);
            $this->assertArrayHasKey('ASSIGNED_BY_ID', $fields);
            $this->assertStringContainsString('Телефон: +79990000040', $fields['SOURCE_DESCRIPTION']);
        });
    }

    public function test_bitrix_failure_keeps_main_success_and_marks_bitrix_failed(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response([
            'error' => 'ERROR_CORE',
            'error_description' => 'Lead rejected',
        ], 200)]);

        $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
            'phone' => '+7 (999) 000-00-04',
        ]));

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $request = RequestConsultation::firstOrFail();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'request_type' => RequestDeliveryStatus::REQUEST_CONSULTATION,
            'request_id' => $request->id,
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'ERROR_CORE',
            'error_message' => 'Lead rejected',
        ]);
    }

    public function test_tracking_database_failure_does_not_break_main_request(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 444], 200)]);

        Schema::rename('request_delivery_statuses', 'request_delivery_statuses_backup');

        try {
            $response = $this->postJson(route('request_consultation.store'), $this->consultationPayload([
                'phone' => '+7 (999) 000-00-05',
            ]));
        } finally {
            Schema::rename('request_delivery_statuses_backup', 'request_delivery_statuses');
        }

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000005',
        ]);
    }

    public function test_ack_url_generation_failure_does_not_break_real_lead(): void
    {
        Queue::fake();
        Http::fake(['*' => Http::response(['result' => 555], 200)]);

        $realUrlGenerator = app('url');

        // Proxy the already initialized Laravel UrlGenerator.
        // All normal URL methods are delegated to the real instance;
        // only signed URL generation is forced to fail.
        $urlMock = \Mockery::mock($realUrlGenerator);

        $urlMock->shouldReceive('temporarySignedRoute')
            ->once()
            ->andThrow(new \RuntimeException('URL signing unavailable'));

        URL::swap($urlMock);

        try {
            $response = $this->postJson(
                '/request-consultation',
                $this->consultationPayload([
                    'phone' => '+7 (999) 000-00-09',
                ])
            );
        } finally {
            // Never leak the mocked URL generator into following tests.
            URL::swap($realUrlGenerator);
        }

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('tracking.ack_url', null);

        $this->assertNotEmpty($response->json('tracking_id'));

        $this->assertDatabaseHas('request_consultations', [
            'phone' => '+79990000009',
        ]);
    }

    public function test_valid_signed_ack_updates_uis_and_yandex_statuses(): void
    {
        $request = RequestConsultation::create([
            'name' => 'Ack Client',
            'phone' => '+79990000006',
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        $uisResponse = $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'meta' => ['form_id' => $request->form_id],
        ]);

        $uisResponse->assertOk()->assertJsonPath('success', true);

        $ymResponse = $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
            'meta' => ['goal' => 'calculator'],
        ]);

        $ymResponse->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ]);

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ]);
    }

    public function test_browser_ack_cannot_change_bitrix_status(): void
    {
        $request = RequestConsultation::create([
            'name' => 'Ack Client',
            'phone' => '+79990000007',
            'form_id' => 'modal-form-header',
        ]);

        $response = $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_BITRIX,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ]);

        $response->assertUnprocessable();
    }

    public function test_browser_ack_database_write_failure_does_not_return_fake_success(): void
    {
        $request = RequestConsultation::create([
            'name' => 'Ack DB Failure',
            'phone' => '+79990000010',
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        DB::statement('PRAGMA query_only = ON');

        try {
            $response = $this->postJson($this->ackUrl($request->tracking_id), [
                'channel' => RequestDeliveryStatus::CHANNEL_UIS,
                'status' => RequestDeliveryStatus::STATUS_SENT,
            ]);
        } finally {
            DB::statement('PRAGMA query_only = OFF');
        }

        $response->assertStatus(503)
            ->assertJsonPath('success', false);
    }

    public function test_sent_cannot_be_downgraded_by_later_unconfirmed_ack(): void
    {
        $request = RequestConsultation::create([
            'name' => 'Monotonic Sent',
            'phone' => '+79990000011',
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ]);
    }

    public function test_failed_cannot_be_downgraded_to_unconfirmed(): void
    {
        $request = $this->trackedConsultation('+79990000015');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ]);
    }

    public function test_failed_cannot_be_downgraded_to_unavailable(): void
    {
        $request = $this->trackedConsultation('+79990000016');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_UNAVAILABLE,
            'error_code' => 'comagic_not_available',
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ]);
    }

    public function test_unconfirmed_can_upgrade_to_failed(): void
    {
        $request = $this->trackedConsultation('+79990000017');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'uis_failed',
        ]);
    }

    public function test_failed_can_upgrade_to_sent(): void
    {
        $request = $this->trackedConsultation('+79990000018');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_FAILED,
            'error_code' => 'ym_exception',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'error_code' => null,
        ]);
    }

    public function test_sent_remains_terminal(): void
    {
        $request = $this->trackedConsultation('+79990000019');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertOk();

        foreach ([
            RequestDeliveryStatus::STATUS_FAILED,
            RequestDeliveryStatus::STATUS_UNAVAILABLE,
            RequestDeliveryStatus::STATUS_UNCONFIRMED,
        ] as $status) {
            $this->postJson($this->ackUrl($request->tracking_id), [
                'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
                'status' => $status,
                'error_code' => 'late_' . $status,
            ])->assertOk();
        }

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
            'error_code' => null,
        ]);
    }

    public function test_processing_to_sent_preserves_started_at_and_attempts(): void
    {
        $request = $this->trackedConsultation('+79990000020');
        $tracker = app(RequestDeliveryTracker::class);

        $tracker->markProcessing(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            RequestDeliveryStatus::CHANNEL_BITRIX,
            $request->tracking_id
        );

        $startedAt = DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_BITRIX)
            ->value('started_at');

        $tracker->markSent(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            RequestDeliveryStatus::CHANNEL_BITRIX,
            ['tracking_id' => $request->tracking_id],
            false
        );

        $status = DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_BITRIX)
            ->first();

        $this->assertSame($startedAt, $status->started_at);
        $this->assertSame(1, (int) $status->attempts);
        $this->assertSame(RequestDeliveryStatus::STATUS_SENT, $status->status);
    }

    public function test_processing_to_failed_preserves_started_at_and_attempts(): void
    {
        $request = $this->trackedConsultation('+79990000021');
        $tracker = app(RequestDeliveryTracker::class);

        $tracker->markProcessing(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            RequestDeliveryStatus::CHANNEL_BITRIX,
            $request->tracking_id
        );

        $startedAt = DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_BITRIX)
            ->value('started_at');

        $tracker->markFailed(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            RequestDeliveryStatus::CHANNEL_BITRIX,
            [
                'tracking_id' => $request->tracking_id,
                'error_code' => 'ERROR_CORE',
            ],
            false
        );

        $status = DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_BITRIX)
            ->first();

        $this->assertSame($startedAt, $status->started_at);
        $this->assertSame(1, (int) $status->attempts);
        $this->assertSame(RequestDeliveryStatus::STATUS_FAILED, $status->status);
    }

    public function test_browser_timeout_and_late_callback_count_as_one_attempt(): void
    {
        $request = $this->trackedConsultation('+79990000022');

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertOk();

        $status = DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_UIS)
            ->first();

        $this->assertSame(RequestDeliveryStatus::STATUS_SENT, $status->status);
        $this->assertSame(1, (int) $status->attempts);
    }

    public function test_unconfirmed_can_be_upgraded_to_sent(): void
    {
        $request = RequestConsultation::create([
            'name' => 'Late Callback',
            'phone' => '+79990000012',
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_UNCONFIRMED,
            'error_code' => 'callback_timeout',
        ])->assertOk();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertOk();

        $this->assertDatabaseHas('request_delivery_statuses', [
            'tracking_id' => $request->tracking_id,
            'channel' => RequestDeliveryStatus::CHANNEL_YANDEX_METRIKA,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ]);
    }

    public function test_stale_pending_is_recognized_by_admin_problem_filter(): void
    {
        $this->actingAsTestUser();

        $request = RequestConsultation::create([
            'name' => 'Stale Pending Client',
            'phone' => '+79990000013',
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        DB::table('request_delivery_statuses')
            ->where('tracking_id', $request->tracking_id)
            ->where('channel', RequestDeliveryStatus::CHANNEL_UIS)
            ->update([
                'status' => RequestDeliveryStatus::STATUS_PENDING,
                'updated_at' => now()->subMinutes(RequestDeliveryStatus::PENDING_STALE_MINUTES + 1),
            ]);

        $response = $this->get(route('admin.request_consultations.index', ['delivery_problem' => 1]));

        $response->assertOk()
            ->assertSee('Stale Pending Client')
            ->assertSee('Нет подтверждения');
    }

    public function test_old_request_without_statuses_is_not_treated_as_failed(): void
    {
        $this->actingAsTestUser();

        RequestConsultation::create([
            'name' => 'Old No Status Client',
            'phone' => '+79990000014',
            'form_id' => 'modal-form-header',
        ]);

        $response = $this->get(route('admin.request_consultations.index', ['delivery_problem' => 1]));

        $response->assertOk()
            ->assertDontSee('Old No Status Client');
    }

    public function test_ack_rejects_invalid_signature_unknown_tracking_id_and_invalid_payload(): void
    {
        $trackingId = (string) Str::uuid();

        $this->postJson('/request-tracking/ack?tracking_id=' . $trackingId, [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertForbidden();

        $this->postJson($this->ackUrl($trackingId), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertNotFound();

        $request = RequestConsultation::create([
            'name' => 'Ack Client',
            'phone' => '+79990000008',
            'form_id' => 'modal-form-header',
        ]);

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => RequestDeliveryStatus::CHANNEL_UIS,
            'status' => 'maybe',
        ])->assertUnprocessable();

        $this->postJson($this->ackUrl($request->tracking_id), [
            'channel' => 'unknown',
            'status' => RequestDeliveryStatus::STATUS_SENT,
        ])->assertUnprocessable();
    }

    private function consultationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Client',
            'phone' => '+7 (999) 000-00-01',
            'form_id' => 'modal-form-header',
            'policy' => '1',
        ], $overrides);
    }

    private function productPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Product Client',
            'phone' => '+7 (999) 000-00-02',
            'form_id' => 'modal-product',
            'data' => json_encode([['id' => 999]], JSON_THROW_ON_ERROR),
            'total_price' => 12345,
            'car' => 'Toyota',
            'policy' => '1',
        ], $overrides);
    }

    private function ackUrl(string $trackingId): string
    {
        return URL::temporarySignedRoute(
            'request-tracking.ack',
            now()->addMinutes(5),
            ['tracking_id' => $trackingId]
        );
    }

    private function createCar(): int
    {
        $now = now();

        $makeId = DB::table('car_makes')->insertGetId([
            'title' => 'Toyota',
            'slug' => 'toyota',
            'description' => 'Toyota',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $modelId = DB::table('car_models')->insertGetId([
            'title' => 'Camry',
            'slug' => 'camry',
            'description' => 'Camry',
            'car_make_id' => $makeId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return DB::table('cars')->insertGetId([
            'title' => 'Toyota Camry',
            'slug' => 'toyota-camry',
            'car_model_id' => $modelId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function assertSingleBitrixLeadPayload(callable $callback): void
    {
        Http::assertSentCount(1);
        Http::assertSent(function ($request) use ($callback): bool {
            $payload = $request->data();

            $this->assertSame('Y', $payload['params']['REGISTER_SONET_EVENT'] ?? null);
            $this->assertSame('Y', $payload['params']['ALLOW_SAVE_DUPLICATE'] ?? null);

            $callback($payload['fields'] ?? []);

            return true;
        });
    }

    private function assertNoBitrixCustomFields(array $fields): void
    {
        $customFields = array_values(array_filter(
            array_keys($fields),
            fn ($field) => str_starts_with((string) $field, 'UF_CRM_')
        ));

        $this->assertSame([], $customFields);
    }

    private function trackedConsultation(string $phone): RequestConsultation
    {
        $request = RequestConsultation::create([
            'name' => 'Tracked Client',
            'phone' => $phone,
            'form_id' => 'modal-form-header',
        ]);

        app(RequestDeliveryTracker::class)->initialize(
            RequestDeliveryStatus::REQUEST_CONSULTATION,
            $request->id,
            $request->tracking_id,
            ['form_id' => $request->form_id]
        );

        return $request;
    }

    private function actingAsTestUser(): void
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Test Admin';
        $user->email = 'admin@example.test';

        $this->actingAs($user);
    }
}
