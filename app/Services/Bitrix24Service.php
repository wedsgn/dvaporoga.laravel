<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Bitrix24Service
{
private static bool $invalidMetrikaClientIdFieldLogged = false;

public function addLead(array $fields): array
{
    $url = rtrim(config('services.bitrix24.webhook'), '/').'/crm.lead.add.json';

    $payload = [
        'fields' => [
            'TITLE'              => $fields['TITLE'] ?? 'Заявка с сайта',
            'NAME'               => $fields['NAME'] ?? null,
            'LAST_NAME'          => $fields['LAST_NAME'] ?? null,
            'COMMENTS'           => $fields['COMMENTS'] ?? null,
            'SOURCE_ID'          => $fields['SOURCE_ID'] ?? (config('services.bitrix24.source_id') ?: '26'),
            'SOURCE_DESCRIPTION' => $fields['SOURCE_DESCRIPTION'] ?? null,
            'ASSIGNED_BY_ID'     => $fields['ASSIGNED_BY_ID'] ?? config('services.bitrix24.responsible_id'),
            'PHONE'              => empty($fields['PHONE']) ? [] : [[ 'VALUE'=>$fields['PHONE'], 'VALUE_TYPE'=>'WORK' ]],
            'EMAIL'              => empty($fields['EMAIL']) ? [] : [[ 'VALUE'=>$fields['EMAIL'], 'VALUE_TYPE'=>'WORK' ]],
            'UTM_SOURCE'         => $fields['UTM_SOURCE']   ?? null,
            'UTM_MEDIUM'         => $fields['UTM_MEDIUM']   ?? null,
            'UTM_CAMPAIGN'       => $fields['UTM_CAMPAIGN'] ?? null,
            'UTM_TERM'           => $fields['UTM_TERM']     ?? null,
            'UTM_CONTENT'        => $fields['UTM_CONTENT']  ?? null,
        ],
        'params' => ['REGISTER_SONET_EVENT' => 'Y', 'ALLOW_SAVE_DUPLICATE' => 'Y'],
    ];

    $metrikaClientIdField = $this->appendMetrikaClientIdField($payload['fields'], $fields);

    // ⚠️ Не логируем персональные данные целиком
    $safePayload = $payload;
    if (!empty($safePayload['fields']['PHONE'][0]['VALUE'])) {
        $safePayload['fields']['PHONE'][0]['VALUE'] = '***masked***';
    }
    if (!empty($safePayload['fields']['EMAIL'][0]['VALUE'])) {
        $safePayload['fields']['EMAIL'][0]['VALUE'] = '***masked***';
    }
    if ($metrikaClientIdField && !empty($safePayload['fields'][$metrikaClientIdField])) {
        $safePayload['fields'][$metrikaClientIdField] = '***masked***';
    }

    try {
        Log::info('B24 request', ['url' => $url, 'payload' => $safePayload]);

        $resp = Http::asForm()->timeout(10)->retry(2, 500)->post($url, $payload);

        Log::info('B24 http', [
            'status' => $resp->status(),
            'json'   => $resp->json(),
        ]);

        return [
            'ok'       => $resp->successful() && isset($resp['result']),
            'status'   => $resp->status(),
            'response' => $resp->json(),
        ];
    } catch (\Throwable $e) {
        Log::error('B24 exception', ['message'=>$e->getMessage()]);
        return ['ok'=>false, 'status'=>0, 'response'=>['exception'=>$e->getMessage()]];
    }
}

private function appendMetrikaClientIdField(array &$payloadFields, array $sourceFields): ?string
{
    $clientId = $this->normalizeMetrikaClientId($sourceFields['METRIKA_CLIENT_ID'] ?? null);
    if ($clientId === null) {
        return null;
    }

    $field = $this->configuredMetrikaClientIdField();
    if ($field === null) {
        return null;
    }

    $payloadFields[$field] = $clientId;

    return $field;
}

private function normalizeMetrikaClientId(mixed $value): ?string
{
    if (
        $value === null ||
        is_bool($value) ||
        is_array($value) ||
        (is_object($value) && !$value instanceof \Stringable)
    ) {
        return null;
    }

    if (!is_scalar($value) && !$value instanceof \Stringable) {
        return null;
    }

    try {
        $clientId = trim((string) $value);
    } catch (\Throwable $e) {
        return null;
    }

    return $clientId !== '' && strlen($clientId) <= 100 ? $clientId : null;
}

private function configuredMetrikaClientIdField(): ?string
{
    $value = config('services.bitrix24.metrika_client_id_field');

    if (!is_scalar($value) && !$value instanceof \Stringable) {
        return null;
    }

    $field = trim((string) $value);
    if ($field === '') {
        return null;
    }

    if (preg_match('/^UF_CRM_[A-Z0-9_]+$/', $field) === 1) {
        return $field;
    }

    if (!self::$invalidMetrikaClientIdFieldLogged) {
        Log::warning('Invalid BITRIX24_METRIKA_CLIENT_ID_FIELD skipped: expected UF_CRM_* technical code.');
        self::$invalidMetrikaClientIdFieldLogged = true;
    }

    return null;
}
}
