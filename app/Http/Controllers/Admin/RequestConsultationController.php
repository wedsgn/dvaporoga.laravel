<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestConsultationController extends Controller
{
  public function index()
  {
      $user = Auth::user();
      $request_consultations = RequestConsultation::orderBy('id', 'DESC')->paginate(50);
      return view('admin.request_consultations.index', compact('request_consultations', 'user'));
  }

  public function show($id)
  {
      $item = RequestConsultation::findOrFail($id);
      $user = Auth::user();
      return view('admin.request_consultations.show', compact('item', 'user'));
  }
  public function search(Request $request)
  {
      $user = Auth::user();
      if (request('search') == null) :
          $request_consultations = RequestConsultation::orderBy('id', 'DESC')->paginate(50);
      else :
          $request_consultations = RequestConsultation::filter()->paginate(50);
      endif;
      return view('admin.request_consultations.index', compact('request_consultations', 'user'));
  }
}

