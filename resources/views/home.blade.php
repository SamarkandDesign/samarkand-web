@extends('layouts.full_width')

@section('title')
Crossroads of Culture
@endsection

@section('head')
<meta name="description" content="Unique and distinctive lampshades, lighting and home accessories sourced from vintage textiles from around the world">
@endsection

@section('content')
<div class="fade-in">

<flicker>
	<carousel-cell image="/img/home/lampshade1-2018.jpg">
		<carousel-copy headline='Lovely lampshades'>
			<p>Hand-made lampshades in vintage sarees and artisan dyed fabrics</p>
			<a class='btn btn-primary btn-lg' href='/shop/lampshades'>Shop lampshades</a>
		</carousel-copy>
	</carousel-cell>

	<carousel-cell image="/img/home/lampbase-banner2-2018.jpg">
		<carousel-copy headline='Stunning lamp bases' style="text-align: right; right: 10%;">
			<p>A range of unique vintage and contemporary lamp bases</p>
			<a class='btn btn-primary btn-lg' href='/shop/lamp-bases'>Shop Lamp Bases</a>
		</carousel-copy>
	</carousel-cell>

	<carousel-cell image="/img/home/hero-kanthas-lc.jpg">
		<carousel-copy headline='Vintage Throws'>
			<p>Throws, kanthas, cushions and more made from vintage fabrics</p>
			<a class='btn btn-primary btn-lg' href='/shop/home-furnishings'>Shop home furnishings</a>
		</carousel-copy>
	</carousel-cell>
</flicker>
</div>

<div class="container mx-auto px-4" v-pre>
	<div class="flex md:mt-4 mt-2">

		@foreach ([
			['title' => 'Home Furnishings', 'img' => '/img/home/tile1.jpg', 'link' => '/shop/home-furnishings'],
			['title' => 'Lighting', 'img' => '/img/home/Shibori_2.jpg', 'link' => '/shop/lighting'],
			['title' => 'Accessories', 'img' => '/img/home/tile3.jpg', 'link' => '/shop/accessories'],
		] as $item)
			<div class="md:mr-2 mr-1">
					<a class="block relative text-white bg-black" href="{{ $item['link'] }}">
						<img src="{{ $item['img'] }}" alt="Samarkand Home Furnishings hover:opacity-75" class="w-100">
							<div class="absolute text-center pin flex justify-center items-center">
							<h2 class='text-uppercase'>{{ $item['title'] }}</h2>
							</div>
					</a>
				</div>
		@endforeach

		{{-- <div class="md:mr-2 mr-1">
			<a class="block" href="/shop/home-furnishings">
				<img src="/img/home/tile1.jpg" alt="Samarkand Home Furnishings" class="product-grid-image img-responsive" style="width:100%;">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Home Furnishings</h2>
					</div>
				</div>
			</a>
		</div>

		<div class="md:mx-2 mx-1">
			<a class="block" href="/shop/lighting">
				<img src="/img/home/Shibori_2.jpg" alt="Samarkand Lighting" class="product-grid-image img-responsive" style="width:100%;">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Lighting</h2>
					</div>
				</div>
			</a>
		</div>

		<div class="md:ml-2 ml-1">
			<a class="block" href="/shop/accessories">
				<img src="/img/home/tile3.jpg" alt="Samarkand Accessories" class="product-grid-image img-responsive" style="width:100%;">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Accessories</h2>
					</div>
				</div>
			</a>
		</div> --}}
	</div>

	<div class="md:mt-4 mt-2 pb-4">
		<flicker>
			@foreach($featured_products as $product)
				<div class="home_featured_image">
				@include('shop._product_tile', compact('product'))
				</div>
			@endforeach
		</flicker>
	</div>



</div>

@endsection

@section('scripts')
<style type="text/css">
.home-page-logo {
	width: 20%;
	height: auto;
}
@media (max-width: 767px) {
	.home-page-logo {
		width: 34%;
	}
}
</style>
@endsection
