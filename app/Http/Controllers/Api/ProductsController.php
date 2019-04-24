<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ProductsController extends Controller
{

  public function feed()
  {
    return response('This exists no longer', 410)->header('Content-Type', 'text/plain; charset=utf-8');
  }
}
