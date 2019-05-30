@extends('layouts.main')

@section('title')
Payment
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="/shop">Shop</a></li>
    <li><a href="/cart">Cart</a></li>
    <li><a href="/checkout">Checkout</a></li>
    <li class="active">Pay</li>
</ol>

@endsection

@section('content')

<h1 class="page-heading">Payment</h1>

<h2 class="text-2xl">Order Details</h2>

@include('orders._summary')

<form action="" id="pay-now-form" class="row">
    <div class="flex justify-end">
        <button type="submit" class="btn btn-success btn-lg w-full sm:w-auto text-center">Pay now</button>
    </div>
</form>
@stop

@section('scripts')
<script>
    (function() {
            const stripe = Stripe('{{ config('services.stripe.publishable') }}');
            const sessionId = '{{ $session_id }}';

            document.querySelector('#pay-now-form').addEventListener('submit', function(e) {
                e && e.preventDefault();

                stripe.redirectToCheckout({ sessionId }).then(function (result) {
                    if (result.error && result.error.message) {
                        alert(result.error.message);
                    }
                });
            });
        })();
</script>
@stop
