<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBanner;
use App\Services\UploadFiles;
use App\Support\UploadValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageBannerController extends Controller
{
  public function __construct(
    protected UploadFiles $uploadFiles
  ) {}

  public function store(Request $request, Page $page)
  {
    $data = $request->validate([
      'title'         => ['nullable', 'string', 'max:255'],
      'sort_order'    => ['nullable', 'integer'],
      'is_active'     => ['nullable', 'boolean'],
      'image_desktop' => UploadValidation::imageRules(),
      'image_mobile'  => UploadValidation::imageRules(),
    ], UploadValidation::messages(['image_desktop', 'image_mobile']));

    $data['page_id']   = $page->id;
    $data['is_active'] = $request->boolean('is_active');

    $banner = DB::transaction(function () use ($request, $data) {
      $banner = PageBanner::create($data);

      if ($request->hasFile('image_desktop')) {
        $path = $this->uploadFiles->imageConvertAndStore(
          $request,
          $request->file('image_desktop'),
          $banner->id
        );
        $banner->update(['image_desktop' => $path]);
      }

      if ($request->hasFile('image_mobile')) {
        $path = $this->uploadFiles->imageConvertAndStore(
          $request,
          $request->file('image_mobile'),
          $banner->id
        );
        $banner->update(['image_mobile' => $path]);
      }

      return $banner->fresh();
    });

    $html = view('admin.pages.partials.banner-card', [
      'banner' => $banner,
    ])->render();

    return response()->json([
      'status' => 'ok',
      'id'     => $banner->id,
      'html'   => $html,
    ]);
  }
  public function edit(PageBanner $banner)
  {
    $user = Auth::user();
    $page = $banner->page;

    return view('admin.pages.banners.edit', compact('banner', 'page', 'user'));
  }

  public function update(Request $request, PageBanner $banner)
  {
    $data = $request->validate([
      'title'         => ['nullable', 'string', 'max:255'],
      'sort_order'    => ['nullable', 'integer'],
      'is_active'     => ['nullable', 'boolean'],
      'image_desktop' => UploadValidation::imageRules(),
      'image_mobile'  => UploadValidation::imageRules(),
    ], UploadValidation::messages(['image_desktop', 'image_mobile']));

    $data['is_active'] = $request->boolean('is_active');

    $banner = DB::transaction(function () use ($request, $banner, $data) {
      if ($request->hasFile('image_desktop')) {
        $path = $this->uploadFiles->imageConvertAndStore(
          $request,
          $request->file('image_desktop'),
          $banner->id
        );
        $data['image_desktop'] = $path;
      }

      if ($request->hasFile('image_mobile')) {
        $path = $this->uploadFiles->imageConvertAndStore(
          $request,
          $request->file('image_mobile'),
          $banner->id
        );
        $data['image_mobile'] = $path;
      }

      $banner->update($data);

      return $banner->fresh();
    });

    $html = view('admin.pages.partials.banner-card', [
      'banner' => $banner,
    ])->render();

    return response()->json([
      'status' => 'ok',
      'id'     => $banner->id,
      'html'   => $html,
    ]);
  }
  public function destroy(Request $request, PageBanner $banner)
  {
    $id      = $banner->id;
    $pageSlug = $banner->page->slug;

    $banner->delete();

    return response()->json([
      'status' => 'ok',
      'id'     => $id,
    ]);
  }
}
