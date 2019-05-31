@extends('layouts.main')

@section('title')
Order Completed
@endsection

@section('content')
@include('partials.errors')

<h1 class="page-heading">Order Completed</h1>

<p>Your order details are below. We'll email you an order confirmation shortly.</p>

<ul>
  <li>Order ID: {{ $order->id }}</li>
  <li>Date: {{ $order->created_at ? $order->created_at->format('j M Y H:i') : 'unknown' }}</li>
  <li>Total: {{ $order->amount }}</li>
</ul>

<h2 class="text-2xl">Order Summary</h2>
@include('orders._summary')

<div class="md:w-1/2">
  <skd-feedback route="/api/feedbacks" order-id="{{ $order->id }}" />
</div>
@stop

@section('scripts')
@if (App::environment(['production', 'testing']))
@include('orders._ga_ecommerce')
@endif
@stop