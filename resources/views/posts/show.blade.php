@extends('layouts.main')

@section('title')
{{ $post->title }}
@endsection

@section('head')
@if ($post->meta_description)
<meta name="description" content="{{ $post->meta_description }}">
@endif
@endsection

@section('content')

<h1>{{ $post->title }}</h1>
<p>
<time datetime="{{ $post->created_at }}">{{ $post->created_at->format('j M Y') }}</time>
</p>
@if($post->categories->count())
<p class="small hint-text">
	{{ $post->categories->map(function($c) { return $c->term; })->implode(', ') }}
	
</p>
@endif

<article class="top-buffer has-images">
{!! $post->getContentHtml() !!}
</article>

@stop
