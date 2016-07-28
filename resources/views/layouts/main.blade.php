@extends('layouts.outer')

@section('body')

<div class="bg">
	@yield('section-header')
    @yield('breadcrumb')
    <div class="container top-buffer">
        

        @include('partials.alert')
        @yield('content')
    </div>
<div class="top-buffer"></div>
</div>
@stop