<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class WelcomePageController extends Controller
{
  public function index()
  {

    return view('welcome');
  }
}
