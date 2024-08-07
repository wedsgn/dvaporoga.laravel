<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RequestConsultation\StoreRequest as RequestConsultationStoreRequest;
use App\Http\Requests\Client\RequestProduct\StoreRequest as RequestProductStoreRequest;
use App\Jobs\RequestConsultationMailSendJob;
use App\Jobs\RequestProductMailSendJob;
use App\Models\Product;
use App\Models\RequestConsultation;
use App\Models\RequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{

  public function store_request_consultation(RequestConsultationStoreRequest $request)
  {
    $request_consultation = new RequestConsultation();
    $request_consultation->fill($request->validated());
    $request_consultation->save();
    $this->send_request_consultation($request_consultation->name, $request_consultation->phone, $request_consultation->form_id);

    return response()->json(['message' => 'Request created successfully'], 201);
  }

  public function store_request_product(RequestProductStoreRequest $request)
  {
    $request_consultation = new RequestProduct();
    $request_consultation->fill($request->validated());
    $data = json_decode($request->validated()['data'], true);
    $products = [];
    foreach ($data as $key => $value) :
      if (isset($value['id'])) :
        $products[] = $value['id'];
      endif;
    endforeach;

    $request_consultation->data = json_encode($products);
    $request_consultation->save();
    $this->send_request_product(
      $request_consultation->name,
      $request_consultation->phone,
      $request_consultation->form_id,
      $request_consultation->data,
      $request_consultation->total_price,
      $request_consultation->car
    );
    return response()->json(['message' => 'Request created successfully'], 201);
  }
  protected function send_request_consultation($name, $phone, $form_id)
  {
    $details = [
      'subject' => 'Заявка на консультацию',
      'name' => $name,
      'phone' => $phone,
      'form' => $form_id
    ];

    //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
    dispatch(new RequestConsultationMailSendJob($details));
  }
  protected function send_request_product(
    $name,
    $phone,
    $form_id,
    $data,
    $total_price,
    $car
  ) {
    $products = [];
    foreach (json_decode($data) as $key => $value) :
      $products[] = Product::find($value);
    endforeach;
    $details = [
      'subject' => 'Заявка на детали',
      'name' => $name,
      'phone' => $phone,
      'products' => $products,
      'total_price' => $total_price,
      'car' => $car,
      'form' => $form_id,
    ];

    //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
    dispatch(new RequestProductMailSendJob($details));
  }
}
