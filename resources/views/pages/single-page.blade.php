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
<h1 class="page-heading">{{ $page->title }}</h1>
<div class="vspace-4">
  {!! $page->getContentHtml() !!}
</div>
@stop