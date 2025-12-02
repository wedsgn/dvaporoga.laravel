<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBanner;
use App\Services\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
      'image_desktop' => ['nullable', 'image', 'max:5120'],
      'image_mobile'  => ['nullable', 'image', 'max:5120'],
    ]);

    $data['page_id']   = $page->id;
    $data['is_active'] = $request->boolean('is_active');

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
      'image_desktop' => ['nullable', 'image', 'max:5120'],
      'image_mobile'  => ['nullable', 'image', 'max:5120'],
    ]);

    $data['is_active'] = $request->boolean('is_active');

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
    $banner->refresh();

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
