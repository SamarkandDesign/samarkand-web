@extends('layouts.main')

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="/shop">Shop</a></li>
        <li><a href="/cart">Cart</a></li>
        <li><a href="/checkout">Checkout</a></li>
        <li class="active">Pay</li>
    </ol>

@endsection

@section('content')

    <h1>Pay</h1>

    <h2>Order Details</h2>

    @include('orders._summary')

    <div class="well">
        <card-form
        route="/payments"
        stripe-key="{{ config('services.stripe.publishable') }}"
        billing-name="{{ $order->billing_address->name }}"
        :billing-address="{
            name: '{{ $order->billing_address->name }}',
            address_line1: '{{ $order->billing_address->line_1 }}',
            address_line2: '{{ $order->billing_address->line_2 }}',
            address_city: '{{ $order->billing_address->city ?: $order->billing_address->line_2 }}',
            address_zip: '{{ $order->billing_address->postcode }}',
            address_country: '{{ $order->billing_address->country }}'
        }">
        {{ csrf_field() }}
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        </card-form>
    </div>
    
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop
