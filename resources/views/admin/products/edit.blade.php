@extends('admin.layouts.admin')

@section('title')
Edit Product
@stop

@section('heading')
Edit Product
@stop

@section('admin.content')

<p class="top-buffer">
	<a href="{{ route('admin.products.index') }}" class="btn btn-link"><i class="fa fa-chevron-left"></i> All Products</a>
	<a href="{{ $product->url }}" class="btn btn-default pull-right">View Product</a>
</p>

@include('partials.errors')

<div class="post-form row" id="postForm">

  {!! Form::model($product, ['route' => ['admin.products.update', $product->id], 'method' => 'PATCH', 'novalidate' => true]) !!}

  @include('admin.products.form', ['submitText' => 'Update'])

  {!! Form::close() !!}

  <div class="col-md-8 col-md-pull-4">
      @include('admin.media._media_adder', ['model' => $product])
  </div>

</div>

@stop
