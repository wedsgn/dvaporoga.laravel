<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MainInfo\UpdateRequest;
use App\Models\MainInfo;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $main_info = MainInfo::first();
        return view('admin.admin',compact('user','main_info'));
    }
    public function edit_info()
    {
        $user = Auth::user();
        $item = MainInfo::first();
        return view('admin.main_info_edit',compact('user','item'));
    }

    public function update_info(UpdateRequest $request, $main_info_id)
    {
        $main_info = MainInfo::whereId($main_info_id)->first();
        $data = $request->validated();

        $main_info->update($data);
        return redirect()->route('admin.index')->with('status', 'main_info-updated');
    }
}
