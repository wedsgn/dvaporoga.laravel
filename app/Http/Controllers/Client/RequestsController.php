<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RequestConsultation\StoreRequest as RequestConsultationStoreRequest;
use App\Http\Requests\Client\RequestProduct\StoreRequest as RequestProductStoreRequest;
use App\Models\RequestConsultation;
use App\Models\RequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{

    public function store_request_onsultation(RequestConsultationStoreRequest $request, $form)
    {
        $request_consultation = new RequestConsultation();
        $request_consultation->fill($request->validated());
        $request_consultation->form_id = $form;
        $request_consultation->save();
        return response()->json(['message' => 'Request created successfully'], 201);
    }

    public function store_request_product(RequestProductStoreRequest $request, $form)
    {
        $request_consultation = new RequestProduct();
        $request_consultation->fill($request->validated());
        $request_consultation->form_id = $form;
        $request_consultation->data = json_encode(['product_id' => $request->product_id]);
        $request_consultation->save();
        return response()->json(['message' => 'Request created successfully'], 201);
    }
}

