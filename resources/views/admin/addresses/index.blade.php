@extends('admin.layouts.admin')

@section('title')
Venues
@stop

@section('heading')
Venues
@stop

@section('admin.content')


<div class="box box-primary clearfix">

	<div class="box-body">

		<table class="table table-striped">
			<thead>
				<tr>
					<th>Address</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($addresses as $address)
				<tr>
					<td>{{ $address->toOneLineString() }}</td>
					<td>
						<a href="{{ route('admin.addresses.edit', $address) }}">Edit</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<!-- Pagination -->
		{!! $addresses->render() !!}
	</div>
</div>


@stop
