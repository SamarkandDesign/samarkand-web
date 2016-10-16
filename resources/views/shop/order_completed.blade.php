@extends('layouts.main')

@section('title')
Order Completed
@endsection

@section('content')
<h1>Order Completed</h1>

<p>Your order details are below</p>

<ul>
  <li>Order ID: {{ $order->id }}</li>
  <li>Date: {{ $order->created_at }}</li>
  <li>Total: {{ $order->amount }}</li>
</ul>

<h2>Order Summary</h2>
@include('orders._summary')

@stop

@section('scripts')
  @if (App::environment(['production', 'testing']))
	 @include('orders._ga_ecommerce')
  @endif
@stop
