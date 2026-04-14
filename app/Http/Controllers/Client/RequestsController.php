<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use App\Http\Requests\Client\RequestConsultation\StoreRequest as RequestConsultationStoreRequest;
use App\Http\Requests\Client\RequestProduct\StoreRequest as RequestProductStoreRequest;
use App\Http\Requests\Client\RequestCar\StoreRequest as RequestCarStoreRequest;

use App\Jobs\RequestConsultationMailSendJob;
use App\Jobs\RequestProductMailSendJob;
use App\Jobs\RequestCarMailSendJob;

use App\Models\Product;
use App\Models\RequestConsultation;
use App\Models\RequestProduct;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Car;
use App\Notifications\TelegramNotificationConsultation;
use App\Notifications\TelegramNotificationProduct;
use App\Notifications\TelegramNotificationCar;

use App\Services\Bitrix24Service;
use App\Services\Forms\FormSubmissionCooldownService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RequestsController extends Controller
{
  /* ===================== PUBLIC ===================== */

  public function store_request_consultation(
    RequestConsultationStoreRequest $request,
    Bitrix24Service $b24,
    FormSubmissionCooldownService $cooldown
  ) {
    if ($response = $this->acquireCooldownOrRespond($request->input('phone'), $cooldown)) {
      return $response;
    }

    try {
      $rc = new RequestConsultation();
      $rc->fill($request->validated());
      $rc->save();

      $site  = $this->siteTag($request);
      $title = $this->titleRu('Заявка с сайта', $rc->form_id, $site);

      $this->send_request_consultation($rc, $title);

      try {
        $utm = $this->resolveUtm($request);

        $res = $b24->addLead([
          'TITLE'   => $title,
          'NAME'    => preg_replace('/[_\*]/', ' ', $rc->name),
          'PHONE'   => $rc->phone,
          'EMAIL'   => $rc->email ?? null,

          'SOURCE_DESCRIPTION' => $this->comments([
            'form_id'     => $rc->form_id,
            'form_ru'     => $this->formLabelRu($rc->form_id),
            'current_url' => $request->input('current_url'),
            'extra'       => [
              'Имя'     => $rc->name,
              'Телефон' => $rc->phone,
            ],
          ], $request),

          'UTM_SOURCE'   => $utm['utm_source'],
          'UTM_MEDIUM'   => $utm['utm_medium'],
          'UTM_CAMPAIGN' => $utm['utm_campaign'],
          'UTM_TERM'     => $utm['utm_term'],
          'UTM_CONTENT'  => $utm['utm_content'],
        ]);

        Log::info('B24 response (consultation)', [
          'status' => $res['status'] ?? null,
          'body'   => $res['response'] ?? null,
        ]);

        if (!($res['ok'] ?? false)) {
          Log::warning('B24 lead add failed (consultation)', $res);
        }
      } catch (\Throwable $e) {
        Log::error('B24 consult exception: ' . $e->getMessage());
      }

      return response()->json(['success' => true], 201);
    } catch (\Throwable $e) {
      $cooldown->release($request->input('phone'));
      throw $e;
    }
  }

  public function store_request_product(
    RequestProductStoreRequest $request,
    Bitrix24Service $b24,
    FormSubmissionCooldownService $cooldown
  ) {
    if ($response = $this->acquireCooldownOrRespond($request->input('phone'), $cooldown)) {
      return $response;
    }

    try {
      $rp = new RequestProduct();
      $rp->fill($request->validated());

      $raw = json_decode($request->validated()['data'] ?? '[]', true) ?: [];
      $ids = [];
      foreach ($raw as $row) {
        if (isset($row['id'])) {
          $ids[] = (int) $row['id'];
        }
      }

      $rp->data = json_encode($ids);
      $rp->save();

      $site  = $this->siteTag($request);
      $title = $this->titleRu('Заявка с сайта', $rp->form_id, $site);

      $this->send_request_product($rp, $title);

      try {
        $utm = $this->resolveUtm($request);

        $items = [];
        foreach ($ids as $pid) {
          if ($p = Product::find($pid)) {
            $items[] = "#{$p->id} {$p->title}";
          }
        }

        $res = $b24->addLead([
          'TITLE'   => $title,
          'NAME'    => preg_replace('/[_\*]/', ' ', $rp->name),
          'PHONE'   => $rp->phone,
          'EMAIL'   => $rp->email ?? null,

          'SOURCE_DESCRIPTION' => $this->comments([
            'form_id'     => $rp->form_id,
            'form_ru'     => $this->formLabelRu($rp->form_id),
            'current_url' => $request->input('current_url'),
            'extra'       => [
              'Авто'   => $rp->car,
              'Итого'  => $rp->total_price,
              'Товары' => $items ? implode("\n", $items) : '—',
            ],
          ], $request),

          'UTM_SOURCE'   => $utm['utm_source'],
          'UTM_MEDIUM'   => $utm['utm_medium'],
          'UTM_CAMPAIGN' => $utm['utm_campaign'],
          'UTM_TERM'     => $utm['utm_term'],
          'UTM_CONTENT'  => $utm['utm_content'],
        ]);

        Log::info('B24 response (product)', [
          'status' => $res['status'] ?? null,
          'body'   => $res['response'] ?? null,
        ]);

        if (!($res['ok'] ?? false)) {
          Log::warning('B24 lead add failed (product)', $res);
        }
      } catch (\Throwable $e) {
        Log::error('B24 product exception: ' . $e->getMessage());
      }

      return response()->json(['success' => true], 201);
    } catch (\Throwable $e) {
      $cooldown->release($request->input('phone'));
      throw $e;
    }
  }

  public function store_request_car(
    RequestCarStoreRequest $request,
    Bitrix24Service $b24,
    FormSubmissionCooldownService $cooldown
  ) {
    if ($response = $this->acquireCooldownOrRespond($request->input('phone'), $cooldown)) {
      return $response;
    }

    try {
      $rc = new RequestConsultation();
      $rc->fill([
        'name'    => $request->input('name'),
        'phone'   => $request->input('phone'),
        'form_id' => $request->input('form_id', 'car-page-form'),
      ]);
      $rc->save();

      $site  = $this->siteTag($request);
      $title = $this->titleRu('Заявка с сайта', $rc->form_id, $site);

      $car = Car::with(['car_model'])->find($request->integer('car_id'));

      $carHuman = $car?->title ?: '—';
      $modelTitle = $car?->car_model?->title ?? null;

      $makeTitle = null;
      if ($car?->car_model && method_exists($car->car_model, 'car_make')) {
        $makeTitle = $car->car_model->car_make?->title ?? null;
      }

      $details = [
        'subject'     => $title,
        'name'        => preg_replace('/[_\*]/', ' ', (string) $rc->name),
        'phone'       => $rc->phone,

        'car_id'      => $car?->id,
        'car'         => $carHuman,
        'make'        => $makeTitle,
        'model'       => $modelTitle,

        'form'        => $this->formLabelRu($rc->form_id),
        'current_url' => $request->input('current_url'),
      ];

      dispatch(new RequestCarMailSendJob($details));

      try {
        $utm = $this->resolveUtm($request);

        $res = $b24->addLead([
          'TITLE'   => $title,
          'NAME'    => $details['name'] ?: null,
          'PHONE'   => $details['phone'],
          'EMAIL'   => null,

          'SOURCE_DESCRIPTION' => $this->comments([
            'form_id'     => $rc->form_id,
            'form_ru'     => $details['form'],
            'current_url' => $details['current_url'],
            'extra'       => [
              'Авто'    => $carHuman,
              'Car ID'  => $details['car_id'] ?: '—',
              'Марка'   => $makeTitle ?: '—',
              'Модель'  => $modelTitle ?: '—',
              'Телефон' => $rc->phone,
            ],
          ], $request),

          'UTM_SOURCE'   => $utm['utm_source'],
          'UTM_MEDIUM'   => $utm['utm_medium'],
          'UTM_CAMPAIGN' => $utm['utm_campaign'],
          'UTM_TERM'     => $utm['utm_term'],
          'UTM_CONTENT'  => $utm['utm_content'],
        ]);

        Log::info('B24 response (car-page)', [
          'status' => $res['status'] ?? null,
          'body'   => $res['response'] ?? null,
        ]);

        if (!($res['ok'] ?? false)) {
          Log::warning('B24 lead add failed (car-page)', $res);
        }
      } catch (\Throwable $e) {
        Log::error('B24 car-page exception: ' . $e->getMessage());
      }

      return response()->json(['success' => true], 201);
    } catch (\Throwable $e) {
      $cooldown->release($request->input('phone'));
      throw $e;
    }
  }
  private function acquireCooldownOrRespond(
    ?string $phone,
    FormSubmissionCooldownService $cooldown
  ): ?JsonResponse {
    if ($cooldown->acquire($phone)) {
      return null;
    }

    $retryAfter = $cooldown->retryAfter($phone);
    $minutes = max(1, (int) ceil($retryAfter / 60));

    $message = "Запрос уже отправлен. Повторная отправка будет доступна через {$minutes} мин.";

    return response()->json([
      'success'     => false,
      'message'     => $message,
      'retry_after' => $retryAfter,
      'errors'      => [
        'phone' => [$message],
      ],
    ], 429)->header('Retry-After', (string) $retryAfter);
  }
  /* ===================== NOTIFICATIONS ===================== */

  protected function send_request_consultation(RequestConsultation $rc, string $subject): void
  {
    $details = [
      'subject' => $subject,
      'name'    => preg_replace('/[_\*]/', ' ', $rc->name),
      'phone'   => $rc->phone,
      'form'    => $this->formLabelRu($rc->form_id),
    ];

    // Telegram
    //     try {
    //     $rc->notify(new TelegramNotificationConsultation($details));
    // } catch (\Throwable $e) {
    //     Log::error('Telegram consultation exception: '.$e->getMessage(), [
    //         'request_consultation_id' => $rc->id,
    //     ]);
    // }

    // Mail (queue)
    dispatch(new RequestConsultationMailSendJob($details));
  }

  protected function send_request_product(RequestProduct $rp, string $subject): void
  {
    $products = [];
    foreach (json_decode($rp->data, true) ?: [] as $id) {
      if ($p = Product::find($id)) $products[] = $p;
    }

    $details = [
      'subject'     => $subject,
      'name'        => preg_replace('/[_\*]/', ' ', $rp->name),
      'phone'       => $rp->phone,
      'products'    => $products,
      'total_price' => $rp->total_price,
      'car'         => $rp->car,
      'form'        => $this->formLabelRu($rp->form_id),
    ];

    // try {
    //     $rp->notify(new TelegramNotificationProduct($details));
    // } catch (\Throwable $e) {
    //     Log::error('Telegram product exception: '.$e->getMessage(), [
    //         'request_product_id' => $rp->id,
    //     ]);
    // }

    dispatch(new RequestProductMailSendJob($details));
  }

    /* ===================== HELPERS ===================== */

  /** Человеческая метка формы по form_id (англ. id → рус.) */
  private function formLabelRu(?string $formId): string
  {
    $map = [
      // модалки
      'modal-form-header'   => 'Шапка',
      'modal-form-faq'      => 'FAQ',
      'modal-form-about'    => 'О нас',
      'modal-form-delivery' => 'Доставка',
      'modal-form-hero'     => 'Слайдер',

      // формы на страницах
      'modal-product'       => 'Модальное окно товара',
      'index-hero-form'   => 'Главная',
      'car-single-form-home' => 'Главная (форма заявки)',
      'catalog-form'      => 'Каталог',
      'product-section'   => 'Секция товара',
      'car-page-form'     => 'Страница авто',
      'partnership-form'  => 'Сотрудничество',
    ];
    return $map[$formId] ?? ($formId ?: 'Без идентификатора');
  }

  private function titleRu(string $context, ?string $formId, string $site): string
  {
    // context + (рус. ярлык формы) + домен
    return $context . ' (' . $this->formLabelRu($formId) . ') ' . $site;
  }

  private function siteTag(\Illuminate\Http\Request $request): string
  {
    $fromConfig = parse_url(config('app.url'), PHP_URL_HOST);
    return $fromConfig ?: $request->getHost();
  }

  private function comments(array $ctx, \Illuminate\Http\Request $request): string
  {
    $lines = [];

    if (!empty($ctx['form_id'])) {
      $lines[] = 'Форма: ' . $ctx['form_id']
        . ' (' . ($ctx['form_ru'] ?? $this->formLabelRu($ctx['form_id'])) . ')';
    }

    $page = $this->resolvePageUrl($ctx, $request);
    if ($page) $lines[] = 'Страница: ' . $page;

    if (!empty($ctx['ip'])) $lines[] = 'IP: ' . $ctx['ip'];

    if (!empty($ctx['extra']) && is_array($ctx['extra'])) {
      foreach ($ctx['extra'] as $k => $v) {
        if ($v === null || $v === '') continue;
        $lines[] = $k . ': ' . $v;
      }
    }

    return implode("\n", $lines);
  }

  /**
   * UTM: берём только то, что реально пришло из формы/URL.
   * Если пусто — отправляем null (Bitrix поля останутся пустыми).
   */
  private function resolveUtm(\Illuminate\Http\Request $request): array
  {
    $keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    $out  = array_fill_keys($keys, null);
    foreach ($keys as $k) {
      $v = $request->input($k);
      if ($v !== null && $v !== '') $out[$k] = $v;
    }
    return $out;
  }

  /** Укоротить строку по символам (safe для UTF-8) */
  private function shorten(string $s, int $max): string
  {
    $s = trim($s);
    if (function_exists('mb_strlen') && mb_strlen($s) > $max) {
      return rtrim(mb_substr($s, 0, $max - 1)) . '…';
    }
    if (strlen($s) > $max) {
      return rtrim(substr($s, 0, $max - 1)) . '…';
    }
    return $s;
  }
  private function cleanUrl(?string $url): ?string
  {
    $url = trim((string)$url);
    if ($url === '') return null;

    $url = html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $url = rawurldecode($url);

    $url = strtok($url, '?#');

    return $url !== '' ? $url : null;
  }

  private function resolvePageUrl(array $ctx, \Illuminate\Http\Request $request): ?string
  {
    $candidate = $ctx['current_url'] ?? null;
    if ($candidate) {
      $clean = $this->cleanUrl($candidate);
      if ($clean) return $clean;
    }

    $ref = $request->headers->get('referer');
    if ($ref) {
      $clean = $this->cleanUrl($ref);
      if ($clean) return $clean;
    }

    $clean = $this->cleanUrl($request->fullUrl());
    return $clean;
  }
}
