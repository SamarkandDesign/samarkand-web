@extends('emails.layout')

@section('content')
<p>Product stock low: {{ $product->sku }}</p>

<p><a href="{{ url($product->url) }}">{{ $product->name }}</a></p>
@stop
