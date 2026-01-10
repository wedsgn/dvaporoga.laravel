<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class CarAjaxController extends Controller
{
    public function models(Request $request)
    {
        $request->validate(['make_id' => 'required|integer']);
        $models = CarModel::where('car_make_id', $request->integer('make_id'))
            ->orderBy('title','asc')
            ->get(['id','title']);

        return response()->json($models);
    }
}
