@extends('emails.layout')

@section('content')
    <p>You have a new customer order</p>

    <p>Order details:</p>

    <ul>
    <li><strong>Order ID:</strong> <a href="{{ url("/admin/orders/{$order->id}") }}">#{{ $order->id }}</a></li>
    <li><strong>Order Date:</strong> {{ $order->created_at }}</li>    
    </ul>

    @include('orders._summary')
@stop