@extends('layouts.full_width')

@section('content')
	{{--
<div class="home-slider">
	@include('partials._carousel', ['images' => [
	'https://unsplash.it/1500/600/?image=' . mt_rand(1,1087),
	'https://unsplash.it/1500/600/?image=' . mt_rand(1,1087)
	]])

</div> --}}

<carousel>
	<slider>
		<img src="https://unsplash.it/1500/600/?image={{mt_rand(1,1087)}}">
	</slider>
	<slider>
		<img src="https://unsplash.it/1500/600/?image={{mt_rand(1,1087)}}">
	</slider>
	<slider>
		<img src="https://unsplash.it/1500/600/?image={{mt_rand(1,1087)}}">
	</slider>
</carousel>


<div class="container">
	<p class="text-center">
		You are at Crueset! This is the front-end of the site, which currently doesn't exist. You can <a href="{{ route('auth.login') }}">login</a> or <a href="{{ route('auth.register') }}">register</a> to get started.
	</p>
</div>

@endsection
