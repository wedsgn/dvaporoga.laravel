<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CatalogImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CatalogImportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.imports.import_catalog', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        Excel::import(new CatalogImport, $request->file('file'));

        return back()->with([
            'success' => 'Импорт выполнен.',
        ]);
    }
}
