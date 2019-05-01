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

    <h1>Pay</h1>

    <h2>Order Details</h2>

    @include('orders._summary')

    <form action="" id="pay-now-form" class="row">
        <div class="col-sm-12 col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success btn-lg btn-block">Pay now</button>
        </div>
    </form>
@stop

@section('scripts')
    <script>
        (function() {
            const stripe = Stripe('{{ config('services.stripe.publishable') }}');
            const sessionId = '{{ $session_id }}';

            document.querySelector('#pay-now-form').addEventListener('submit', e => {
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
