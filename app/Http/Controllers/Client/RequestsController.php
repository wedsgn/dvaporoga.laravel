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
use App\Notifications\InvoicePaid;
use App\Notifications\TelegramNotificationProduct;
use App\Notifications\TelegramNotificationConsultation;

use Notification;

class RequestsController extends Controller
{

  public function store_request_consultation(RequestConsultationStoreRequest $request)
  {
    $request_consultation = new RequestConsultation();
    $request_consultation->fill($request->validated());
    $request_consultation->save();
    $this->send_request_consultation($request_consultation);
    return response()->json(['message' => 'Request created successfully'], 201);
  }

  public function store_request_product(RequestProductStoreRequest $request)
  {
    $request_product = new RequestProduct();
    $request_product->fill($request->validated());
    $data = json_decode($request->validated()['data'], true);

    $products = [];
    foreach ($data as $key => $value) :
      if (isset($value['id'])) :
        $products[] = $value['id'];
      endif;
    endforeach;

    $request_product->data = json_encode($products);
    $request_product->save();
    $this->send_request_product($request_product);
    return response()->json(['message' => 'Request created successfully'], 201);
  }

  public function request_product_section(RequestProductSectionStoreRequest $request)
  {
    $request_product = new RequestProduct();
    $request_product->fill($request->validated());
    $products = [
      $request->validated()['product_id']
    ];
    $price = Price::where('id', $request->validated()['price_id'])->first()->one_side;
    $request_product->data = json_encode($products);
    $request_product->total_price = $price;
    $request_product->form_id = $request->validated()['form_id'];
    $request_product->save();
    $this->send_request_product($request_product);
  }

  protected function send_request_consultation($request_consultation)
  {
    $details = [
      'subject' => 'заявка на консультацию',
      'name' => preg_replace('/[_\*]/', ' ', $request_consultation->name),
      'phone' => $request_consultation->phone,
      'form' => $request_consultation->form_id
    ];

    $request_consultation->notify(new TelegramNotificationConsultation($details));
    //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
    dispatch(new RequestConsultationMailSendJob($details));
  }
  protected function send_request_product($request_product) {
    $products = [];
    foreach (json_decode($request_product->data) as $key => $value) :
      $products[] = Product::find($value);
    endforeach;
    $details = [
      'subject' => 'заявка на детали',
      'name' => preg_replace('/[_\*]/', ' ', $request_product->name),
      'phone' => $request_product->phone,
      'products' => $products,
      'total_price' => $request_product->total_price,
      'car' => $request_product->car,
      'form' => $request_product->form_id,
    ];

    $request_product->notify(new TelegramNotificationProduct($details));
    //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
    dispatch(new RequestProductMailSendJob($details));
  }
}
