@extends('layouts.main')

@section('title')
Events
@endsection

@section('head')
<meta name="description" content="Upcoming events, sales and fairs featuring Samarkand Design.">
@endsection

@section('content')

<h1>Events</h1>

<p>
	<a href="{{route('events.index', ['before' => $earliestDate])}}">Past Events</a>
</p>

@if($events->count() === 0) 
<p>No events for this period.</p>
@endif

<div class="row">
@foreach ($events as $event)


	<a href="/event/{{ $event->slug }}" class="event-tile-container col-md-4 col-sm-6 col-xs-12 top-buffer">
	<div class="event-tile thumbnail">
		
		<div class="event-tile-image">
		<img src="{{ $event->featured_image }}" alt="" class="img-responsive">	
			
		</div>
		<div class="caption">
			<p class="text-muted small">
				{{ $event->present()->startDate() }}
			</p>
			<h3>{{ $event->title }}</h3>
			<small>{{ $event->venue->toOneLineString() }}</small>
		</div>
	</div>
	</a>

@endforeach
</div>
@stop
