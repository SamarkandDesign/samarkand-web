@extends('admin.layouts.admin')

@section('title')
Edit Address
@stop

@section('heading')
Edit Address
@stop

@section('admin.content')

@include('partials.errors')

<div class="post-form row" id="postForm">
	{!! Form::model($address, ['route' => ['addresses.update', $address], 'method' => 'PATCH']) !!}
	<div class="col-md-8">
		<div class="box box-primary">

			<div class="box-body">
				@include('addresses.form')
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="box box-primary">

			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-6">
						<label for="lat">Latitude</label>
						{!! Form::number('lat', null, ['class' => 'form-control', 'id' => 'lat', 'step' => 'any', 'disabled']) !!}
					</div>

					<div class="form-group col-xs-6">
						<label for="lng">Longitude</label>
						{!! Form::number('lng', null, ['class' => 'form-control', 'id' => 'lng', 'step' => 'any', 'disabled']) !!}
					</div>
				</div>
				
				<button type="submit" class="btn btn-success btn-block">Submit</button>

			</div>
		</div>
	</div>
	{!! Form::close() !!}

</div>

@stop
