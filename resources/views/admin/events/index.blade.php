@extends('admin.layouts.admin')

@section('title')
Events
@stop

@section('heading')
{{ $title or 'Events' }}
@stop

@section('admin.content')

<p class="clearfix"><a href="{{route('admin.events.create')}}" class="btn btn-success pull-right">New Event</a></p>

{{-- <p><a href="{{ route('admin.events.index') }}">Upcoming</a> | <a href="{{ route('admin.events.index', ['all' => 1]) }}">All</a></p> --}}


<div class="nav-tabs-custom clearfix">
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ !Request::has('all') ? 'active' : '' }}">
            <a href="{{ route('admin.events.index') }}">Upcoming</a>
        </li>
        <li role="presentation" class="{{ Request::has('all') ? 'active' : '' }}">
            <a href="{{ route('admin.events.index', ['all' => 1]) }}">All</a>
        </li>
    </ul>
	<div class="tab-content">

		<table class="table table-striped">
			<thead>
				<tr>
					<th>Title</th>
					<th>Date</th>
					<th>Venue</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($events as $event)
				<tr>
					<td>
						{{ $event->title }}<br>
						<small>{{ $event->present()->status() }}</small>
						<div class="row-actions">
								<a href="{{ route('events.show', $event->slug) }}">View</a> | 
								<a href="{{ route('admin.events.edit', $event) }}">Edit</a> | 
								<a href="{{ route('admin.events.delete', $event) }}" data-method="delete" data-confirm="Are you sure?" class="text-danger" rel="nofollow">Trash</a>
						</div>
					</td>
					<td>
						<p><strong>Start: </strong>{{ $event->present()->startDate() }}</p>
						<p><strong>End: </strong>{{ $event->present()->endDate() }}</p>

						<p>({{ $event->present()->duration() }})</p>
					</td>
					<td>@include('partials.address', ['address' => $event->venue])</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<!-- Pagination -->
		{!! $events->links() !!}
	</div>
</div>


@stop