@extends('layouts.outer')

@section('body')

@yield('section-header')
<div class="px-4 sm:px-10 flex-auto bg-white">
    <div class="container mx-auto vspace-5 py-10">
        @yield('breadcrumb')
        @include('partials.alert')
        @yield('content')
    </div>
</div>
@stop
