<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\UpdateRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $pages = Page::orderBy('id', 'DESC')->paginate(10);
    return view('admin.pages.index', compact('pages', 'user'));
  }

  public function show($page_slug)
  {
    $user = Auth::user();
    $item = Page::whereSlug($page_slug)
        ->with('banners')
        ->firstOrFail();
    return view('admin.pages.show', compact('item', 'user'));
  }

  public function edit($page_slug)
  {

    $user = Auth::user();
    $item = Page::whereSlug($page_slug)->firstOrFail();

    return view('admin.pages.edit', compact('item', 'user'));
  }

  public function update(UpdateRequest $request, $page_slug)
  {
    $page = Page::whereSlug($page_slug)->firstOrFail();
    $data = $request->validated();
    $page->update($data);
    return redirect()->route('admin.pages.index')->with('status', 'item-updated');
  }

  public function search(Request $request)
  {
    $user = Auth::user();
    if (request('search') == null) :
      $pages = Page::orderBy('id', 'DESC')->paginate(50);
    else :
      $pages = Page::filter()->paginate(50);
    endif;
    return view('admin.pages.index', compact('pages', 'user'));
  }

}
