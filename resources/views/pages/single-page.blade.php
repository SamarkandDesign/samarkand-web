@extends('layouts.main')

@section('title')
{{ $page->title }}
@endsection

@section('head')
@if ($page->meta_description)
<meta name="description" content="{{ $page->meta_description }}">
@endif
@endsection

@section('content')

<h1>{{ $page->title }}</h1>

{!! $page->getContentHtml() !!}

@stop
