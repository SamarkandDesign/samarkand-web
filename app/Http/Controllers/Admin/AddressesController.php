<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
  public function index()
  {
    $addresses = Address::where('addressable_type', 'App\Event')->paginate();

    return view('admin.addresses.index', compact('addresses'));
  }

  public function edit(Address $address)
  {
    return view('admin.addresses.edit', compact('address'));
  }
}
