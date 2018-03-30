@extends('admin.layouts.admin')

@section('title')
Edit Post
@stop

@section('heading')
Edit Post
@stop

@section('admin.content')

@if ($post->status === 'published')
<p class="top-buffer clearfix">
	<a href="{{ $post->url }}" class="btn btn-default pull-right">View Post</a>
</p>
@endif

@include('partials.errors')

<div class="post-form" id="postForm">
  <div class="row">
    {!! Form::model($post, ['route' => ['admin.posts.update', $post->id], 'method' => 'PATCH']) !!}

    @include('admin.posts.form', ['submitText' => 'Update'])

    {!! Form::close() !!}
  </div>
</div>

@stop
