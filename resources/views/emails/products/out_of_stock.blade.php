@extends('emails.layout')

@section('content')
<p>Product out of stock: {{ $product->sku }}</p>

<p><a href="{{ url($product->url) }}">{{ $product->name }}</a></p>
@stop
