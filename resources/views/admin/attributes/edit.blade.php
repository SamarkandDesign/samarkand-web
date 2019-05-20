@extends('admin.layouts.admin')

@section('title')
{{ $title or 'Edit Attribute' }}
@stop

@section('heading')
{{ $title or 'Edit Attribute' }}
@stop

@section('admin.content')

<p>
	<a href="{{ route('admin.attributes.index') }}" class="btn btn-link"><i class="fa fa-chevron-left"></i> All Attributes</a>
    <a href="{{ route('admin.attributes.create') }}" class="btn btn-success pull-right">New Attribute</a>
</p>
<p class="clearfix"></p>

<div class="box box-primary">
    <div class="box-body">

     <cr-attribute-form :product-attribute="{{ $product_attribute->toJson() }}"></cr-attribute-form>

	<h4>Update Property Details</h4>

	@include('partials.errors')
		{!! Form::model($product_attribute, ['route' => ['admin.attributes.update', $product_attribute],'method' => 'patch']) !!}
		<div class="row">
			<div class="form-group col-md-4">
				{!! Form::label('name', 'Property Name') !!}
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
			</div>

			<div class="form-group col-md-4">
				{!! Form::label('slug', 'Slug') !!}
				{!! Form::text('slug', null, ['class' => 'form-control']) !!}
			</div>

			<div class="form-group col-md-4">
				{!! Form::label('order', 'Order') !!}
				{!! Form::number('order', null, ['class' => 'form-control']) !!}
			</div>
		</div>

		<button type="submit" class="btn btn-success">Update</button>

		{!! Form::close() !!}
    </div>
</div>

@stop
