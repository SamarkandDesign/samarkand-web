@extends('admin.layouts.admin')

@section('title')
Order #{{ $order->id }} Details
@stop

@section('heading')
Order #{{ $order->id }} Details
@stop

@section('admin.content')

<div class="box box-primary">
	<div class="box-header">General Details</div>

		<table class="table">
			<tbody>
				<tr>
					<th>Order Date</th>
					<td>{{ $order->created_at }}</td>
				</tr>
				<tr>
					<th>Order Status</th>
					<td>
					{{ $order->present()->status }}
					@include('admin.orders.partials._status_switcher')
					</td>
				</tr>
				<tr>
					<th>Payment ID</th>
					<td>{{ $order->payment_id }}</td>
				</tr>
				<tr>
					<th>Customer</th>
					<td><a href="{{ route('admin.users.edit', $order->customer->username) }}">{{ $order->customer->name }}</a> ({{ $order->email }})</td>
				</tr>
				<tr>
					<td>
						<h4>Billing Address</h4>
						@include('partials.address', ['address' => $order->billing_address])

						Phone: {{ $order->billing_address->phone }}
					</td>
					<td>
						<h4>Shipping Address</h4>
						@include('partials.address', ['address' => $order->shipping_address])
					</td>
				</tr>
				@if ($order->delivery_note)
				<tr>
					<th>Delivery Note</th>
					<td>{{ $order->delivery_note }}</td>
				</tr>
				@endif
			</tbody>
		</table>


</div>

<div class="box box-primary">
	<div class="box-header">Order Items</div>
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th>Item</th>
				<th>SKU</th>
				<th>Cost</th>
				<th>Qty</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($order->product_items as $item)
				<tr>
					@if ($item->orderable)
					<td style="width:36px">{{ $item->orderable->present()->thumbnail(20) }}</td>
					<td><a href="{{ route('admin.products.edit', $item->orderable) }}">{{ $item->description }}</a></td>
					<td>{{ $item->orderable->sku }}</td>
					@else
					<td></td>
					<td>{{ $item->description }} (Deleted)</td>
					<td>N/A</td>
					@endif
					<td>{{ $item->price_paid }}</td>
					<td>{{ $item->quantity }}</td>
					<td>{{ $item->total_paid }}</td>
				</tr>
			@endforeach

			<tr>
				<td colspan="5"></td>
			</tr>

			<tr>
				<td><i class="fa fa-truck"></i></td>
				<td>{{ $order->shipping_item->description}}</td>

				<td colspan="3"></td>
				<td>{{ $order->shipping_item->price_paid }}</td>
			</tr>

		</tbody>
		<tfoot>
			<tr>
				<th colspan="4"></th>
				<th>Grand Total:</th>
				<th>{{ $order->amount }}</th>
			</tr>
		</tfoot>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
	<h3>Order Updates</h3>
		<ul class="timeline">

@foreach ($order->order_notes as $note)
    <!-- timeline item -->
    <li>
        <!-- timeline icon -->
        <i class="fa fa-{{ $note->icon }} bg-blue"></i>
        <div class="timeline-item">
            <time class="time"><i class="fa fa-clock-o"></i> {{ $note->created_at }}</time>

            <h3 class="timeline-header">{{ $order->user_id ? $order->user->name : ''}}</h3>

            <div class="timeline-body">
                {{ $note->body }}
            </div>

        </div>
    </li>
    <!-- END timeline item -->
@endforeach
<li> <i class="fa fa-clock-o bg-gray"></i> </li>
</ul>
	</div>
</div>

@stop
