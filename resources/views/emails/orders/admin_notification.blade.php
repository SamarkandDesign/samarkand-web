@extends('emails.layout')

@section('content')
    <p>You have a new customer order</p>

    <p>Order details:</p>

    <ul>
    <li><strong>Order ID:</strong> <a href="{{ url("/admin/orders/{$order->id}") }}">#{{ $order->id }}</a></li>
    <li><strong>Order Date:</strong> {{ $order->created_at }}</li>    
    </ul>

    @include('orders._summary')
	
	@if ($order->delivery_note)
	    <p><strong>Delivery Note:</strong></p>
		<p>{{ $order->delivery_note }}</p>
    @endif
@stop