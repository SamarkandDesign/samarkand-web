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
	<carousel-cell image="/img/home/hero-2.jpg">
		<carousel-copy headline='Lovely lampshades'>
			<p>Hand-made silk lampshades from India</p>
			<a class='btn btn-primary btn-lg' href='/shop/lampshades'>Shop Lampshades</a>
		</carousel-copy>
	</carousel-cell>

	<carousel-cell image="/img/home/hero-kanthas-lc.jpg">
		<carousel-copy headline='Vintage Throws'>
			<p>Throws, kanthas, cushions and more made from vintage fabrics</p>
			<a class='btn btn-primary btn-lg' href='/shop/home-furnishings'>Shop Home Furnishings</a>
		</carousel-copy>
	</carousel-cell>
</flicker>
</div>

<div class="container" v-pre>
	<div class="row">

		<div class="col-sm-4 top-buffer">
			<a class="product-grid-element" href="/shop/home-furnishings">
				<img src="/img/home/tile1.jpg" alt="Samarkand Home Furnishings" class="product-grid-image img-responsive">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Home Furnishings</h2>
					</div>
				</div>
			</a>
		</div>

		<div class="col-sm-4 top-buffer">
			<a class="product-grid-element" href="/shop/lighting">
				<img src="/img/home/Shibori_2.jpg" alt="Samarkand Lighting" class="product-grid-image img-responsive">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Lighting</h2>
					</div>
				</div>
			</a>
		</div>

		<div class="col-sm-4 top-buffer">
			<a class="product-grid-element" href="/shop/accessories">
				<img src="/img/home/tile3.jpg" alt="Samarkand Accessories" class="product-grid-image img-responsive">
				<div class="product-flex">
					<div class="product-description">
						<h2 class='text-uppercase'>Accessories</h2>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="top-buffer"></div>
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
