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


<h2>Order Summary</h2>

<table class="table">
<thead>
  <th></th>
  <th>Product</th>
  <th>Total Price</th>
</tr>
</thead>
<tbody>
@foreach (Cart::content() as $item)
<tr>
  <td class="td-thumbnail">{{ $item->model->present()->thumbnail(45) }}</td>
  <td>{{ $item->name }} x{{ $item->qty }}</td>
  <td>{{ new App\Values\Price($item->model->getPrice()->value() * $item->qty) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
  <th colspan="2"></th>
  <th>{{ config('shop.currency_symbol') }}{{ Cart::total() }}</th>
</tr>
</tfoot>
</table>

@include('partials.errors')

<div id="checkout">
  <form action="/orders" method="POST" id="checkout-form">
    {{ csrf_field() }}

    {{-- Saved Addresses --}}
    @if (Auth::check() and Auth::user()->addresses->count())
    <div class="row">

      <div class="col-md-6">
        <h3>Billing Address</h3>
        @foreach (Auth::user()->addresses as $address)
        <div class="radio">
          <label>
            {!! Form::radio('billing_address_id', $address->id, $address->id === old('billing_address_id'), ['id' =>"billing_address_{$address->id}" ]) !!}
            @include('partials.address', compact('address'))
          </label>
        </div>
        @endforeach
      </div>

      <div class="col-md-6">
        <h3>Shipping Address</h3>
        @foreach (Auth::user()->addresses as $address)
        <div class="radio">
          <label>
            {!! Form::radio('shipping_address_id', $address->id, $address->id === old('shipping_address_id'), ['id' => "shipping_address_{$address->id}" ]) !!}
            @include('partials.address', compact('address'))
          </label>
        </div>
        @endforeach

        <div class="form-group">
          <label for="delivery_note">Delivery Notes</label>
          <textarea name="delivery_note" id="del" class="form-control"></textarea>
        </div>
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

        <div slot="delivery-note">
          <div class="form-group">
            <label for="delivery_note">Delivery notes</label>
            <textarea name="delivery_note" id="del" class="form-control"></textarea>
          </div>
        </div>
    </address-form>

    @endif

    <div class="row top-buffer">
    <p class="col-sm-4 col-sm-offset-8 col-md-2 col-md-offset-10">
      <button type="submit" class="btn btn-success btn-lg btn-block">Continue</button>
    </p>
    </div>

  </form>
</div>
@stop
