@extends('layouts.main')

@section('title')
{{ $page->title }}
@endsection

@section('content')

<h1>{{ $page->title }}</h1>

{!! $page->getContentHtml() !!}

@stop
