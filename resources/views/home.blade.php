@extends('layouts.full_width')

@section('title')
Crossroads of Culture
@endsection

@section('head')
<meta name="description" content="Unique and distinctive lampshades, lighting and home accessories sourced from vintage textiles from around the world">
@endsection

@section('content')

<carousel>
	<slider>
		<img src="/img/home/hero-3.jpg">
	</slider>
	<slider>
		<img src="/img/home/hero-kanthas.jpg">
	</slider>
	<slider>
		<img src="/img/home/hero-2.jpg">
	</slider>
	<slider>
		<img src="/img/home/hero-4.jpg">
	</slider>

	<div slot="overlay" style="width:100%;">
		<img src="/img/samarkand-logo-250.svg" class="home-page-logo" alt="Samarkand Design Logo">
	</div>
</carousel>

{{-- 	<div class="container top-buffer text-justify">
		<h1 class="text-center">Samarkand Design</h1>
		<h2 class="text-center">Crossroads of Culture</h2>
		<p>Once a major crossing point on the great trade routes of Central Asia, the historic town of Samarkand now lies in modern day Uzbekistan, a melting pot of global cultures.</p>

		<p>At Samarkand we source vintage textiles from around the world to create unique and distinctive home accessories.  By reinterpreting time-honoured skills and design with modern flair, we create pieces with the perfect balance of tradition and contemporary style.</p>

		<p>From silk lampshades, hand-crafted from vintage sarees, to framed embroideries and textiles, each piece is carefully selected as a beautiful, one-off accent for your home.</p>
	</div> --}}
	<div class="container">
		<div class="row">

			<div class="col-sm-4 top-buffer">
				<a class="product-grid-element" href="/shop/home-furnishings">
					<img src="/img/home/tile1.jpg" alt="Samarkand Home Furnishings" class="product-grid-image img-responsive">
					<div class="product-flex">
						<div class="product-description">
						<h2>HOME FURNISHINGS</h2>
						</div>
					</div>
				</a>		
			</div>

			<div class="col-sm-4 top-buffer">
				<a class="product-grid-element" href="/shop/lighting">
					<img src="/img/home/Shibori_2.jpg" alt="Samarkand Lighting" class="product-grid-image img-responsive">
					<div class="product-flex">
						<div class="product-description">
							<h2>LIGHTING</h2>
						</div>
					</div>
				</a>		
			</div>

			<div class="col-sm-4 top-buffer">
				<a class="product-grid-element" href="/shop/accessories">
					<img src="/img/home/tile3.jpg" alt="Samarkand Accessories" class="product-grid-image img-responsive">
					<div class="product-flex">
						<div class="product-description">
							<h2>ACCESSORIES</h2>
						</div>
					</div>
				</a>		
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
