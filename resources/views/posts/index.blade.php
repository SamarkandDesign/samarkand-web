@extends('layouts.main')

@section('title')
Posts
@endsection

@section('head')

<meta name="description" content="Latest news and blog posts from Samarkand Design">

@endsection

@section('content')

<h1>Posts</h1>

@foreach($posts as $post)
<div class="top-buffer">
	<a href="{{ $post->url}}">
		<h2>{{ $post->title }}</h2>
	</a>
	<time datetime="{{ $post->created_at }}">{{ $post->created_at->format('j M Y') }}</time>

	<article class="top-buffer has-images">
	{!! $post->getContentHtml() !!}
	</article>
</div>
@endforeach

<div class="text-center">
{!! $posts->appends(Request::query())->links() !!}
</div>

@stop
