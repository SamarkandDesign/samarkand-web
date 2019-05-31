@extends('admin.layouts.admin')

@section('title')
Create Event
@stop

@section('heading')
Create Event
@stop

@section('admin.content')

@include('admin.partials.errors')
<div class="row">


	<div class="post-form" id="postForm">
		{!! Form::model($event, ['route' => 'admin.events.store', 'method' => 'POST']) !!}

		@include('admin.events.form')

		{!! Form::close() !!}
	</div>
</div>
@stop