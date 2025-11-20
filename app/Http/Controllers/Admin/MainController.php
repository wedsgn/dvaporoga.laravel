<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MainInfo\UpdateRequest;
use App\Models\MainInfo;
use Illuminate\Support\Facades\Auth;

class MainController extends BaseController
{
  public function index()
  {
    $user = Auth::user();
    $main_info = MainInfo::first();
    return view('admin.admin', compact('user', 'main_info'));
  }
  public function edit_info()
  {
    $user = Auth::user();
    $item = MainInfo::first();
    return view('admin.main_info_edit', compact('user', 'item'));
  }

  public function update_info(UpdateRequest $request, $main_info_id)
  {
    $main_info = MainInfo::whereId($main_info_id)->first();
    $data = $request->validated();
    if ($request->hasFile('company_image')) {
      $data['company_image'] = $this->upload_service->imageConvertAndStore($request, $data['company_image'], 'company_images');
    }

    $main_info->update($data);
    return redirect()->route('admin.index')->with('status', 'main_info-updated');
  }
}
