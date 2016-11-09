@extends('emails.layout')

@section('content')
<p>Product stock low: <a href="{{ url($product->url) }}">{{ $product->name }}</a> ({{ $product->sku }}) - {{ $product->stock_qty }} left</p>
@stop
