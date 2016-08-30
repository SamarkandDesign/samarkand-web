@extends('admin.layouts.admin')

@section('title')
{{ $title or 'Products' }}
@stop

@section('heading')
{{ $title or 'Products' }}
@stop

@section('admin.content')


<div class="row">
	<div class="col-md-9 bottom-buffer">
		<product-search
		key="{{ $searchKey }}"
		app-id="{{ config('scout.algolia.id') }}"
		index-name="{{ $searchIndex }}"
		></product-search>
	</div>

	<div class="col-md-3 bottom-buffer">
		<a href="{{route('admin.products.create')}}" class="btn btn-success pull-right">New Product</a>
	</div>
</div>
<p>
	<a href="{{ route('admin.products.index') }}">All</a> ({{ $productCount }}) | <a href="{{ route('admin.products.trash') }}">Trash</a> ({{ $trashedCount }})
	
</p>

<div class="box box-primary">
	<div class="box-body">

		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Product</th>
					<th>SKU</th>
					<th>Location</th>
					<th>Stock</th>
					<th>Price</th>
					<th>Categories</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($products as $product)
				<tr>
					<td>
						<a href="{{ route("admin.products.edit", $product) }}">
							{{ $product->present()->thumbnail(40) }}
						</a>
					</td>
					<td>
						<strong>{{ $product->name }}</strong>
						<div class="row-actions">
							{{ $product->present()->indexLinks() }}
						</div>
					</td>
					<td>{{ $product->sku }}</td>
					<td>{{ $product->location }}</td>
					<td>{{ $product->stock_qty }}</td>
					<td>{{ $product->present()->price() }}</td>
					<td>{{ $product->present()->categoryList() }}</td>
					<td>
						<i class="fa {{ $product->listed ? 'fa-eye' : 'fa-eye-slash text-muted' }}" title="{{ $product->listed ? 'Listed in online store' : 'Unlisted' }}"></i>
						@if ($product->featured)
						<i class="fa fa-star" title="Featured"></i>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<!-- Pagination -->
		{!! $products->render() !!}
	</div>
</div>


@stop
