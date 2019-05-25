@extends('layouts.full_width')

@section('title')
Crossroads of Culture
@endsection

@section('head')
<meta name="description"
	content="Unique and distinctive lampshades, lighting and home accessories sourced from vintage textiles from around the world">
@endsection

@section('section-header')
<div class="fade-in">
	<flicker class="overflow-hidden h-72">
		@component('components/carousel-cell', ['title' => 'Lovely Lampshades', 'image' => '/img/home/lampshade1-2018.jpg'])
		<p>Hand-made lampshades in vintage sarees and artisan dyed fabrics</p>
		<a class='btn btn-primary btn-lg mt-4' href='/shop/lampshades'>Shop lampshades</a>
		@endcomponent

		@component('components/carousel-cell', ['title' => 'Stunning Lamp Bases', 'image' =>
		'/img/home/lampbase-banner2-2018.jpg'])
		<p>A range of unique vintage and contemporary lamp bases</p>
		<a class='btn btn-primary btn-lg mt-4' href='/shop/lamp-bases'>Shop Lamp Bases</a>
		@endcomponent

		@component('components/carousel-cell', ['title' => 'Vintage Throws', 'image' => '/img/home/hero-kanthas-lc.jpg'])
		<p>Throws, kanthas, cushions and more made from vintage fabrics</p>
		<a class='btn btn-primary btn-lg mt-4' href='/shop/home-furnishings'>Shop home furnishings</a>
		@endcomponent
	</flicker>
</div>
@endsection

@section('content')

<div class="px-2 md:px-4">
	<div class="container mx-auto">
		<div class="flex md:mt-4 mt-2 -mx-1 md:-mx-2" v-pre>
			@foreach ([
			['title' => 'Home Furnishings', 'img' => '/img/home/tile1.jpg', 'link' => '/shop/home-furnishings'],
			['title' => 'Lighting', 'img' => '/img/home/Shibori_2.jpg', 'link' => '/shop/lighting'],
			['title' => 'Accessories', 'img' => '/img/home/tile3.jpg', 'link' => '/shop/accessories'],
			] as $item)
			<div class="md:mx-2 mx-1 flex-1">
				<a class="block relative text-white hover:text-white bg-black" href="{{ $item['link'] }}">
					<img src="{{ $item['img'] }}" alt="Samarkand Home Furnishings" class="w-full trans hover:opacity-75">
					<div class="absolute text-center inset-0 flex justify-center items-center">
						<h2 class='text-uppercase text-3xl'>{{ $item['title'] }}</h2>
					</div>
				</a>
			</div>
			@endforeach
		</div>

		<div class="md:mt-4 mt-2 pb-4">
			<flicker>
				@foreach($featured_products as $product)
				<div class="w-1/2 md:w-1/4 mx-2">
					@include('shop._product_tile', compact('product'))
				</div>
				@endforeach
			</flicker>
		</div>
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