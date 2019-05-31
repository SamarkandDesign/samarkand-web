@extends('admin.layouts.admin')

@section('title')
Edit Event
@stop

@section('heading')
Edit Event
@stop

@section('admin.content')

<p class="top-buffer">
	<a href="{{ route('admin.events.index') }}" class="btn btn-link"><i class="fa fa-chevron-left"></i> All Events</a>
	<a href="/event/{{ $event->slug }}" class="btn btn-default pull-right">View Event</a>
</p>

@include('admin.partials.errors')
<div class="row">


	<div class="post-form" id="postForm">
		{!! Form::model($event, ['route' => ['admin.events.update', $event], 'method' => 'PATCH']) !!}

		@include('admin.events.form')

		{!! Form::close() !!}

	</div>
	<div class="col-md-8">
		@include('admin.media._media_adder', ['model' => $event])
	</div>
</div>
@stop