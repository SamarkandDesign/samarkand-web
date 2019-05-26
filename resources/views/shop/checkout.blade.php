@inject('countries', 'App\Countries\CountryRepository')

@extends('layouts.main')

@section('title')
Checkout
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
  <li><a href="/shop">Shop</a></li>
  <li><a href="/cart">Cart</a></li>
  <li class="active">Checkout</li>
</ol>

@endsection

@section('content')


<h1 class="page-heading">Order Summary</h1>

<table class="w-full">
  <thead>
    <tr class="border-b border-gray-300">
      <th></th>
      <th class="p-2 text-left">Product</th>
      <th class="py-2 pl-2 text-right">Total Price</th>
    </tr>
  </thead>
  <tbody>
    @foreach (Cart::content() as $item)
    <tr class="border-b border-gray-300">
      <td class="py-2 pr-2">{{ $item->model->present()->thumbnail(45) }}</td>
      <td class="py-2">
        <p class="text-bold">{{ $item->name }} </p>
        <p class="text-gray-600 text-sm">x{{ $item->qty }}</p>
      </td>
      <td class="py-2 pl-2 text-right">{{ new App\Values\Price($item->model->getPrice()->value() * $item->qty) }}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2" class="py-2"></th>
      <th class="py-2 text-right">{{ config('shop.currency_symbol') }}{{ Cart::total() }}</th>
    </tr>
  </tfoot>
</table>

@include('partials.errors')

<div id="checkout">
  <form action="/orders" method="POST" id="checkout-form" class="vspace-5">
    {{ csrf_field() }}

    {{-- Saved Addresses --}}
    @if (Auth::check() and Auth::user()->addresses->count())
    <div class="flex -mx-4">

      <div class="w-1/2 vspace-4 px-4">
        <h3>Billing Address</h3>
        @foreach (Auth::user()->addresses as $address)
        <div class="radio">
          <input type="radio" name="billing_address_id" value="{{ $address->id }}"
            checked="{{ $address->id === old('billing_address_id') }}" id="{{ "billing_address_{$address->id}" }}"
            class="radio-panel hidden" />
          <label for={{ "billing_address_{$address->id}" }}
            class="block hover:bg-gray-100 rounded cursor-pointer p-4 border border-gray-300 ">
            @include('partials.address', compact('address'))
          </label>
        </div>
        @endforeach
      </div>

      <div class="w-1/2 vspace-4 px-4">
        <h3>Shipping Address</h3>
        @foreach (Auth::user()->addresses as $address)
        <div class="radio">
          <input type="radio" name="shipping_address_id" value="{{ $address->id }}"
            checked="{{ $address->id === old('shipping_address_id') }}" id="{{ "shipping_address_{$address->id}" }}"
            class="radio-panel hidden" />
          <label for={{ "shipping_address_{$address->id}" }}
            class="block hover:bg-gray-100 rounded cursor-pointer p-4 border border-gray-400 ">
            @include('partials.address', compact('address'))
          </label>
        </div>
        @endforeach

      </div>

    </div>


    <p>
      <a href="{{ route('addresses.create') }}" class="btn btn-default">Add new address</a>
    </p>

    @else

    <address-form :differentshipping="{{ old('different_shipping_address') ? 'true' : 'false' }}">
      <div slot="billing-address">
        @include('partials.address_form', ['type' => 'billing', 'address' => old('address.billing')])
      </div>
      <div slot="shipping-address">
        @include('partials.address_form', ['type' => 'shipping', 'address' => old('address.shipping')])

      </div>


    </address-form>

    @endif


    <div class="form-group md:w-1/2 md:pr-4">
      <label for="del" class="control-label">Delivery notes</label>
      <textarea name="delivery_note" id="del" class="form-control"></textarea>
    </div>


    <div class="row top-buffer">
      <p class="col-sm-4 col-sm-offset-8 col-md-2 col-md-offset-10">
        <button type="submit" class="btn btn-success btn-lg btn-block">Continue</button>
      </p>
    </div>

  </form>
</div>
@stop