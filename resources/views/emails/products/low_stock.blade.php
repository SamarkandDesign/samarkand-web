@extends('emails.layout')

@section('content')
<p>Product stock low: {{ $product->sku }}</p>

<div>{{ $product->description }}</div>
@stop
