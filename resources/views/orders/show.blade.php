@extends('layouts.main')

@section('title')
  Order #{{ $order->id }}
@endsection

@section('content')

<ol class="breadcrumb">
  <li><a href="/account">My Account</a></li>
  <li class="active">View Order</li>
</ol>

<h1>Order #{{$order->id }}</h1>

<p>Order #{{$order->id }} was placed on {{ $order->created_at->toDayDateTimeString() }} and is <strong>{{ $order->present()->status(true) }}</strong>.</p>

@if($order->invoice_id)
  <a href="{{ route('orders.invoices.show', $order) }}" class="btn btn-default" target="_blank">Download Invoice</a>
@endif

@if($order->status === App\Order::PENDING)
    <p>This order is not yet paid for. <a href="{{ route('orders.pay', $order) }}" class="btn btn-default">Pay Now</a></p>
@endif

<h2>Order Details</h2>

@include('orders._summary')

@stop
