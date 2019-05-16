@extends('layouts.outer')

@section('body')

<div class="bg">
	@yield('section-header')
    <div class="container top-buffer mx-auto px-4">
        @yield('breadcrumb')
        @include('partials.alert')
        @yield('content')
    </div>
<div class="top-buffer"></div>
</div>
@stop
