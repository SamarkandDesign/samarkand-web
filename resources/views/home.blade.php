@extends('layouts.full_width')

@section('title')
Crossroads of Culture
@endsection

@section('content')

	<carousel>
		<slider>
			<img src="https://samarkanddesign.com/wp-content/uploads/2016/03/hero-3.jpg">
		</slider>
		<slider>
			<img src="https://samarkanddesign.com/wp-content/uploads/2016/03/hero-kanthas.jpg">
		</slider>
		<slider>
			<img src="https://samarkanddesign.com/wp-content/uploads/2016/03/hero-2.jpg">
		</slider>

		<div slot="overlay" style="width:100%;">
			<img src="/img/samarkand-logo-250.svg" class="" alt="Samarkand Design Logo" style="width: 20%; height: auto;">
			<div class="hidden-xs">
			</div>
		</div>
	</carousel>

	<div class="container top-buffer text-justify">
		<h1 class="text-center">Samarkand Design</h1>
		<h2 class="text-center">Crossroads of Culture</h2>
		<p>Once a major crossing point on the great trade routes of Central Asia, the historic town of Samarkand now lies in modern day Uzbekistan, a melting pot of global cultures.</p>

		<p>At Samarkand we source vintage textiles from around the world to create unique and distinctive home accessories.  By reinterpreting time-honoured skills and design with modern flair, we create pieces with the perfect balance of tradition and contemporary style.</p>

		<p>From silk lampshades, hand-crafted from vintage sarees, to framed embroideries and textiles, each piece is carefully selected as a beautiful, one-off accent for your home.</p>
	</div>

@endsection
