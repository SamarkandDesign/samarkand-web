@extends('layouts.main')

@section('title')
Events
@endsection

@section('head')
<meta name="description" content="Upcoming events, sales and fairs featuring Samarkand Design.">
@endsection

@section('content')

<h1 class="page-heading">Events</h1>

<p>
	<a href="{{route('events.index', ['before' => $earliestDate])}}">Past Events</a>
</p>

@if($events->count() === 0)
<p>No events for this period.</p>
@endif

<div class="flex -mx-4">
	@foreach ($events as $event)


	<a href="/event/{{ $event->slug }}" class="sm:w-1/2 md:w-1/3 block px-4 ">
		<div class="border border-gray-400 rounded overflow-hidden text-gray-800">

			<div class="event-tile-image">
				<img src="{{ $event->featured_image }}" alt="" class="w-100 h-auto">
			</div>
			<div class="p-4">
				<h3 class="text-lg">{{ $event->title }}</h3>
				<p class="text-sm text-gray-600">
					{{ $event->present()->startDate() }}
				</p>
				<small class="text-sm text-gray-500">{{ $event->venue->toOneLineString() }}</small>
			</div>
		</div>
	</a>

	@endforeach
</div>
@stop