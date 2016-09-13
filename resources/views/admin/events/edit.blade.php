@extends('admin.layouts.admin')

@section('title')
Edit Event
@stop

@section('heading')
Edit Event
@stop

@section('admin.content')

@include('partials.errors')
<div class="row">


	<div class="post-form" id="postForm">
		{!! Form::model($event, ['route' => ['admin.events.update', $event], 'method' => 'PATCH']) !!}

		@include('admin.events.form')

		{!! Form::close() !!}
	</div>
</div>
@stop
