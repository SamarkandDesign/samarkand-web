@extends('emails.layout')

@section('content')
    <p>Thank you for your order</p>

    <p>Your order has been received and is now being processed. Your order details are shown below for your reference:</p>

    <ul>
    <li><strong>Order ID:</strong> #{{ $order->id }}</li>
    <li><strong>Order Date:</strong> {{ $order->created_at->format('j M Y') }}</li>
    </ul>

    @include('orders._summary')

    @if (!$order->user->autoCreated())
    <p>You can view the status of your orders on <a href="{{ url('/account') }}">your account page</a>.</p>
    @endif
@stop
