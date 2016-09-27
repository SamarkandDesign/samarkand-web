<script>
	ga('require', 'ecommerce');

	ga('ecommerce:addTransaction', {
		'id': '{{ $order->id }}',
		'affiliation': '{{ config('app.name') }}',
		'revenue': '{{ $order->amount->asDecimal() }}',
		'currency': '{{ config('shop.currency') }}'
	  // 'shipping': ''
	});

	@foreach ($order->product_items as $item)
	ga('ecommerce:addItem', {
		'id': '{{ $order->id }}',
		'name': '{{ $item->description }}',
		'sku': '{{ $item->orderable->sku }}',
		'category': '{{ $item->orderable->product_category->term }}',
		'price': {{ $item->orderable->getPrice()->asDecimal() }},
		'quantity': '{{ $item->quantity }}',
		'currency': '{{ config('shop.currency') }}'

	});
	@endforeach

	ga('ecommerce:send');
</script>