@extends('emails.layout')

@section('content')
<p>Product out of stock: {{ $product->sku }}</p>

<div>{{ $product->description }}</div>
@stop
