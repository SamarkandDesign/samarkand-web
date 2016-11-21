<div class="col-md-4 col-md-push-8">
	<div class="box box-primary" id="postContent">
		<div class="box-body">
			<div class="form-group">
				{!! Form::label('published_at', 'Publish At') !!}
				<div class="date">
					{!! Form::input('datetime-local', 'published_at', old('published_at', isset($product->published_at) ? $product->published_at->format('Y-m-d\TH:i:s') : date('Y-m-d\TH:i:s')), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="row">
				<div class="form-group col-xs-6">
					<label for="price">Price</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-{{ strtolower(config('shop.currency')) }}"></i></span>
						<input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $product->price->asDecimal()) }}">
					</div>
				</div>

				<div class="form-group col-xs-6">
					<label for="sale_price">Sale Price</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-{{ strtolower(config('shop.currency')) }}"></i></span>
						<input type="number" step="0.01" class="form-control" name="sale_price" value="{{ old('sale_price', $product->sale_price->asDecimal()) }}">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-xs-6">
					<label for="name">Stock Quantity</label>
					<input type="number" class="form-control" name="stock_qty" value="{{ old('stock_qty', $product->stock_qty) }}">
				</div>

				<div class="form-group col-xs-6">
					<label for="name">SKU</label>
					<input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}">
				</div>
			</div>


			<div class="form-group">
				{!! Form::label('location', 'Item Location') !!}
				{!! Form::text('location', null, ['class' => 'form-control', 'list' => 'locations']) !!}
				<datalist id="locations">
					@foreach ($productLocations as $productLocation)
					<option value="{{ $productLocation }}">
					@endforeach
				</datalist>
			</div>

			<div class="checkbox">
				<input type="hidden" name="listed" value="0">
				<label>
					{!! Form::checkbox('listed', '1', !$product->exists() ? true : null) !!} Listed in the Online Store
				</label>
			</div>			

			<div class="checkbox">
				<input type="hidden" name="featured" value="0">
				<label>
					{!! Form::checkbox('featured', '1') !!} Featured
				</label>
			</div>

			<div class="form-group">
				{!! Form::label('user_id', 'Author') !!}
				{!! Form::select('user_id', App\User::pluck('name', 'id'), null, ['class' => 'form-control']) !!}
			</div>

			<div>
				<input type="submit" class="btn btn-block btn-success" value="Save Product">
			</div>
		</div>
	</div>

	@include('admin.products.form.attributes')

	<cr-category-chooser taxonomy="product_category" heading="Product Categories" :checkedcategories="{{ isset($selected_product_categories) ? $selected_product_categories->toJson(JSON_NUMERIC_CHECK) : '[]' }}"></cr-category-chooser>

	{{-- <cr-image-chooser image="{{ old('media_id', $product->media_id) }}"></cr-image-chooser> --}}
</div>

<div class="form-group col-md-8 col-md-pull-4">
	<div class="box box-primary" id="postContent">
		<div class="box-body">
			<cr-title-slugger name="name" initial-value="{{ old('name', $product->name) }}" initial-slug="{{ old('slug', $product->slug) }}"></cr-title-slugger>

			<cr-markarea initial="{{ old('description', $product->description) }}" name="description" title="description"></cr-markarea>

		</div>
	</div>
</div>
