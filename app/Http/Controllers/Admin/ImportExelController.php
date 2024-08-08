<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportExelRequest;
use App\Imports\CarsImport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ImportExelController extends Controller
{
  public function import_cars() {

    $user = Auth::user();
    return view('admin.imports.import_cars', compact('user'));
  }
  public function import_products() {

    $user = Auth::user();
    return view('admin.imports.import_products', compact('user'));
  }

    public function store_cars(ImportExelRequest $request) {

      Excel::import(new CarsImport, $request->file('file_exel'));

      return redirect('/admin')->with( 'status', 'import-cars-success');
    }
    public function store_products(ImportExelRequest $request) {

      Excel::import(new ProductsImport, $request->file('file_exel'));

      return redirect('/admin')->with( 'status', 'import-products-success');
    }
}
