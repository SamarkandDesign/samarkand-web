@extends('layouts.main')

@section('title')
Order Completed
@endsection

@section('content')
@include('partials.alerts._errors_block')
<h1>Order Completed</h1>

<p>Your order details are below. We'll email you an order confirmation shortly.</p>

<ul>
  <li>Order ID: {{ $order->id }}</li>
  <li>Date: {{ $order->created_at }}</li>
  <li>Total: {{ $order->amount }}</li>
</ul>

<h2>Order Summary</h2>
@include('orders._summary')

<div class="row">
  <div class="col-sm-8">

    <form action="/feedbacks" method="POST" class="top-buffer">
      {{ csrf_field() }}
      <input type="hidden" name="order_id" value="{{ $order->id }}" />

      <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
        <label class="control-label" for="message">Where did you hear about us?</label>
        <textarea id="message" class="form-control" rows="5" name="message" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary" id="send">Submit</button>
    </form>
  </div>
</div>
@stop

@section('scripts')
  @if (App::environment(['production', 'testing']))
	 @include('orders._ga_ecommerce')
  @endif
@stop
