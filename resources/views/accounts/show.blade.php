@extends('layouts.main')

@section('title')
My Account
@endsection

@section('content')

<ol class="breadcrumb">
    <li class="active">My Account</li>
</ol>

<h1 class="page-heading">My Account</h1>

<p>Hello {{ $user->name }} (not {{ $user->name }}? <a href="/logout">Sign out</a>). From your account dashboard you can
    view your recent orders, <a href="{{ route('addresses.index') }}">manage your addresses</a> and <a
        href="{{ route('accounts.edit') }}">edit your account details</a>.</p>

<h2 class="text-2xl">Recent Orders</h2>
@if ($user->hasOrders())
<table class="w-full">
    <thead>
        <tr>
            <th class="text-left py-2 pr-2 ">Order</th>
            <th class="text-left p-2">Date</th>
            <th class="text-left p-2">Status</th>
            <th class="text-left py-2 pl-2">Total</th>
            <th class="p2"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($user->orders->take(5) as $order)
        <tr class="border-t border-gray-300">
            <td class="py-2 pr-2"><a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
            <td class="p-2">{{ $order->created_at->format('j M Y g:ia') }}</td>
            <td class="p-2">{{ $order->present()->status }}</td>
            <td class="p-2">{{ $order->amount }} for {{ $order->product_items->count() }}
                {{ str_plural('item', $order->product_items->count()) }}</td>
            <td class="py-2 pl-2">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-default">View</a>
                @if($order->status === App\Order::PENDING)
                <a href="{{ route('orders.pay', $order) }}" class="btn btn-default">Pay</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@else
<p>You haven't made any orders yet. Why not visit <a href="/shop">the shop</a>?</p>
@endif

<h2 class="text-2xl">Addresses</h2>
@if ($user->hasOrders())
<p>Last used addresses</p>
<div class="flex -mx-4">
    <div class="w-1/2 px-4">
        <h3 class="text-xl mb-2">Billing Address</h3>
        @include('partials.address', ['address' => $user->orders->first()->billing_address])
    </div>
    <div class="w-1/2 px-4">
        <h3 class="text-xl mb-2">Shipping Address</h3>
        @include('partials.address', ['address' => $user->orders->first()->shipping_address])
    </div>

</div>
@endif
<p>
    <a href="{{ route('addresses.index') }}" class="btn btn-default">Manage Addresses</a>
</p>

@if ($user->trade_discount > 0)
<div>
    <h3>Trade Discount</h3>
    <p>As a trade customer your discount will be automatically applied to each order on all products except sale
        products.</p>
    <p>Your discount is {{$user->trade_discount}}%.</p>
</div>
@endif


@stop