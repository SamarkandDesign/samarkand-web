@extends('admin.layouts.admin')

@section('title')
Create Product
@stop

@section('heading')
Create Product
@stop

@section('admin.content')

@include('partials.errors')

<p class="top-buffer">
	<a href="{{ route('admin.products.index') }}" class="btn btn-link"><i class="fa fa-chevron-left"></i> All Products</a>
</p>

<div class="row post-form" id="postForm">
	{!! Form::model($product, ['route' => 'admin.products.store', 'method' => 'POST', 'novalidate' => true]) !!}
		@include('admin.products.form')
	{!! Form::close() !!}
</div>
@stop