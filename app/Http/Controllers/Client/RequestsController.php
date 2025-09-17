<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RequestConsultation\StoreRequest as RequestConsultationStoreRequest;
use App\Http\Requests\Client\RequestProduct\StoreRequest as RequestProductStoreRequest;
use App\Http\Requests\Client\RequestProductSection\StoreRequest as RequestProductSectionStoreRequest;

use App\Jobs\RequestConsultationMailSendJob;
use App\Jobs\RequestProductMailSendJob;
use App\Models\Price;
use App\Models\Product;
use App\Models\RequestConsultation;
use App\Models\RequestProduct;
use App\Notifications\TelegramNotificationProduct;
use App\Notifications\TelegramNotificationConsultation;

use App\Services\Bitrix24Service;
use Illuminate\Support\Facades\Log;

class RequestsController extends Controller
{
  public function store_request_consultation(
    RequestConsultationStoreRequest $request,
    Bitrix24Service $b24
  ) {
    $rc = new RequestConsultation();
    $rc->fill($request->validated());
    $rc->save();

    // Телега/почта (как было)
    $this->send_request_consultation($rc);

    // → Bitrix24
    try {
      $utm = $this->resolveUtm($request);
      $title = $this->titleByFormId($rc->form_id, 'Заявка на консультацию');

      $res = $b24->addLead([
        'TITLE'   => $title,
        'NAME'    => preg_replace('/[_\*]/', ' ', $rc->name),
        'PHONE'   => $rc->phone,
        'EMAIL'   => $rc->email ?? null,
        'COMMENTS' => $this->comments([
          'form_id'     => $rc->form_id,
          'current_url' => $request->input('current_url'),
          'ip'          => $request->ip(),
        ]),
        'SOURCE_DESCRIPTION' => $request->headers->get('referer'),

        // UTM (с учётом фолбэка)
        'UTM_SOURCE'   => $utm['utm_source'],
        'UTM_MEDIUM'   => $utm['utm_medium'],
        'UTM_CAMPAIGN' => $utm['utm_campaign'],
        'UTM_TERM'     => $utm['utm_term'],
        'UTM_CONTENT'  => $utm['utm_content'],
      ]);
      Log::info('B24 response (controller)', [
        'status' => $res['status'] ?? null,
        'body'   => $res['response'] ?? null,
      ]);
      if (!$res['ok']) Log::warning('B24 lead add failed (consultation)', $res);
    } catch (\Throwable $e) {
      Log::error('B24 consult exception: ' . $e->getMessage());
    }

    return response()->json(['message' => 'Request created successfully'], 201);
  }

  public function store_request_product(
    RequestProductStoreRequest $request,
    Bitrix24Service $b24
  ) {
    $rp = new RequestProduct();
    $rp->fill($request->validated());

    // нормализуем массив id товаров
    $raw = json_decode($request->validated()['data'] ?? '[]', true) ?: [];
    $ids = [];
    foreach ($raw as $row) {
      if (isset($row['id'])) $ids[] = (int)$row['id'];
    }
    $rp->data = json_encode($ids);
    $rp->save();

    // Телега/почта (как было)
    $this->send_request_product($rp);

    // → Bitrix24
    try {
      $utm = $this->resolveUtm($request);

      $items = [];
      foreach ($ids as $pid) {
        if ($p = Product::find($pid)) $items[] = "#{$p->id} {$p->title}";
      }

      $title = $this->titleByFormId($rp->form_id, 'Заявка на детали');

      $res = $b24->addLead([
        'TITLE'   => $title,
        'NAME'    => preg_replace('/[_\*]/', ' ', $rp->name),
        'PHONE'   => $rp->phone,
        'EMAIL'   => $rp->email ?? null,
        'COMMENTS' => $this->comments([
          'form_id'     => $rp->form_id,
          'current_url' => $request->input('current_url'),
          'ip'          => $request->ip(),
          'extra'       => [
            'Авто'   => $rp->car,
            'Итого'  => $rp->total_price,
            'Товары' => $items ? implode("\n", $items) : '—',
          ],
        ]),
        'SOURCE_DESCRIPTION' => $request->headers->get('referer'),

        'UTM_SOURCE'   => $utm['utm_source'],
        'UTM_MEDIUM'   => $utm['utm_medium'],
        'UTM_CAMPAIGN' => $utm['utm_campaign'],
        'UTM_TERM'     => $utm['utm_term'],
        'UTM_CONTENT'  => $utm['utm_content'],
      ]);
      Log::info('B24 response (controller)', [
        'status' => $res['status'] ?? null,
        'body'   => $res['response'] ?? null,
      ]);
      if (!$res['ok']) Log::warning('B24 lead add failed (product)', $res);
    } catch (\Throwable $e) {
      Log::error('B24 product exception: ' . $e->Message());
    }

    return response()->json(['message' => 'Request created successfully'], 201);
  }

  public function request_product_section(
    RequestProductSectionStoreRequest $request,
    Bitrix24Service $b24
  ) {
    $rp = new RequestProduct();
    $rp->fill($request->validated());

    $ids = [(int) $request->validated()['product_id']];
    $price = optional(Price::find($request->validated()['price_id']))->one_side;

    $rp->data        = json_encode($ids);
    $rp->total_price = $price;
    $rp->form_id     = $request->validated()['form_id'];
    $rp->save();

    // Телега/почта (как было)
    $this->send_request_product($rp);

    // → Bitrix24
    try {
      $utm = $this->resolveUtm($request);

      $p = Product::find($ids[0] ?? null);
      $title = $this->titleByFormId($rp->form_id, 'Заявка из секции товара(Главная)');

      $res = $b24->addLead([
        'TITLE'   => $title,
        'NAME'    => preg_replace('/[_\*]/', ' ', $rp->name),
        'PHONE'   => $rp->phone,
        'EMAIL'   => $rp->email ?? null,
        'COMMENTS' => $this->comments([
          'form_id'     => $rp->form_id,
          'current_url' => $request->input('current_url'),
          'ip'          => $request->ip(),
          'extra'       => [
            'Товар' => $p ? "#{$p->id} {$p->title}" : '—',
            'Цена'  => $rp->total_price,
          ],
        ]),
        'SOURCE_DESCRIPTION' => $request->headers->get('referer'),

        'UTM_SOURCE'   => $utm['utm_source'],
        'UTM_MEDIUM'   => $utm['utm_medium'],
        'UTM_CAMPAIGN' => $utm['utm_campaign'],
        'UTM_TERM'     => $utm['utm_term'],
        'UTM_CONTENT'  => $utm['utm_content'],
      ]);
      Log::info('B24 response (controller)', [
        'status' => $res['status'] ?? null,
        'body'   => $res['response'] ?? null,
      ]);
      if (!$res['ok']) Log::warning('B24 lead add failed (section)', $res);
    } catch (\Throwable $e) {
      Log::error('B24 section exception: ' . $e->getMessage());
    }

    return response()->json(['message' => 'Request created successfully'], 201);
  }

  /** ===== Вспомогательные методы ===== */

  protected function send_request_consultation($rc)
  {
    $details = [
      'subject' => 'заявка на консультацию',
      'name'    => preg_replace('/[_\*]/', ' ', $rc->name),
      'phone'   => $rc->phone,
      'form'    => $rc->form_id,
    ];
    $rc->notify(new TelegramNotificationConsultation($details));
    dispatch(new RequestConsultationMailSendJob($details));
  }

  protected function send_request_product($rp)
  {
    $products = [];
    foreach (json_decode($rp->data, true) ?: [] as $id) {
      if ($p = Product::find($id)) $products[] = $p;
    }
    $details = [
      'subject'     => 'заявка на детали',
      'name'        => preg_replace('/[_\*]/', ' ', $rp->name),
      'phone'       => $rp->phone,
      'products'    => $products,
      'total_price' => $rp->total_price,
      'car'         => $rp->car,
      'form'        => $rp->form_id,
    ];
    $rp->notify(new TelegramNotificationProduct($details));
    dispatch(new RequestProductMailSendJob($details));
  }

  /** Разные заголовки лида по form_id */
  private function titleByFormId(?string $formId, string $fallback): string
  {
    $map = [
      'index-hero-form' => 'Заявка (главная)',
      'Форма в шапке'   => 'Заявка (шапка)',
      'Форма каталога'  => 'Заявка (каталог)',
    ];
    return $map[$formId] ?? $fallback;
  }

  /** Единообразный COMMENTS для Bitrix24 */
  private function comments(array $ctx): string
  {
    $lines = [];
    if (!empty($ctx['form_id']))     $lines[] = 'Форма: ' . $ctx['form_id'];
    if (!empty($ctx['current_url'])) $lines[] = 'URL: ' . $ctx['current_url'];
    if (!empty($ctx['ip']))          $lines[] = 'IP: ' . $ctx['ip'];
    if (!empty($ctx['extra']) && is_array($ctx['extra'])) {
      foreach ($ctx['extra'] as $k => $v) {
        if ($v === null || $v === '') continue;
        $lines[] = $k . ': ' . $v;
      }
    }
    return implode("\n", $lines);
  }

  /**
   * Фолбэк UTM: если не пришли из формы — пытаемся определить по Referer.
   * Возвращает массив с ключами utm_*
   */
  private function resolveUtm(\Illuminate\Http\Request $request): array
  {
    $utm = [
      'utm_source'   => $request->input('utm_source'),
      'utm_medium'   => $request->input('utm_medium'),
      'utm_campaign' => $request->input('utm_campaign'),
      'utm_term'     => $request->input('utm_term'),
      'utm_content'  => $request->input('utm_content'),
    ];

    if (!empty($utm['utm_source'])) {
      return $utm; // уже пришли из формы
    }

    $ref = (string) $request->headers->get('referer', '');
    if ($ref === '') {
      $utm['utm_source'] = 'direct';
      $utm['utm_medium'] = 'none';
      return $utm;
    }

    try {
      $host = preg_replace('/^www\./', '', parse_url($ref, PHP_URL_HOST) ?? '');
      if (preg_match('/google\./i', $host)) {
        $utm['utm_source'] = 'google';
        $utm['utm_medium'] = 'organic';
      } elseif (preg_match('/yandex\./i', $host)) {
        $utm['utm_source'] = 'yandex';
        $utm['utm_medium'] = 'organic';
      } elseif (preg_match('/bing\.com/i', $host)) {
        $utm['utm_source'] = 'bing';
        $utm['utm_medium'] = 'organic';
      } else {
        $utm['utm_source'] = $host ?: 'direct';
        $utm['utm_medium'] = $host ? 'referral' : 'none';
      }
    } catch (\Throwable $e) {
      $utm['utm_source'] = 'direct';
      $utm['utm_medium'] = 'none';
    }

    return $utm;
  }
}
