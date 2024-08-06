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
        $request_consultation->data = json_encode(['product_id' => $request->product_id]);
        $request_consultation->save();
        dd($request_consultation->data);

        $this->send_request_product($request_consultation->name, $request_consultation->phone, $request_consultation->form_id, $request_consultation->data);
        return response()->json(['message' => 'Request created successfully'], 201);
    }
    protected function send_request_consultation($name, $phone, $form_id)
    {
        if($form_id == 'index-hero-form'){

          $form_id = 'Заявка с блока "Ремонтные пороги и арки без предоплат"';
        }
        if($form_id == 'footer-form'){

          $form_id = 'Заявка с подвала сайта';
        }
        if($form_id == 'FAQ-form'){

          $form_id = 'Заявка с блока "Часто задаваемые вопросы"';
        }
        $details = [
            'subject' => 'Заявка на консультацию',
            'name' => $name,
            'phone' => $phone,
            'form' => $form_id
                  ];

        //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
        dispatch(new RequestConsultationMailSendJob($details));
    }
    protected function send_request_product($name, $phone, $form_id, $data)
    {
        if($form_id == 'products-section'){

          $form_id = 'Заявка с блока "Фиксированная цена на все модели"';
        }
        if($form_id == 'product-parts-section'){

          $form_id = 'Заявка из каталога сайта';
        }
        $products_ids = collect(json_decode($data, true))->pluck('product_id')->all();
        $data = collect(Product::whereIn('id', $products_ids)->get());
        dd($data);
        $details = [
            'subject' => 'Заявка на детали',
            'name' => $name,
            'phone' => $phone,
            'data' => $data,
            'form' => $form_id
                  ];

        //ДЛЯ ОТПРАВКИ СООБЩЕНИЙ НУЖНО УСТАНОВИТЬ Supervisor НА СЕРВЕР ЧТОБЫ РАБОТАЛИ ОЧЕРЕДИ PasswordResetMailSendJob
        dispatch(new RequestProductMailSendJob($details));
    }
}

