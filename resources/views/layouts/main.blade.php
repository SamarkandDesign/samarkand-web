@extends('layouts.outer')

@section('body')

@yield('section-header')
@yield('breadcrumb')
<div class="px-2 md:px-4 flex-auto">
    <div class="container mx-auto vspace-5 py-10">
        @include('partials.alert')
        @yield('content')
    </div>
</div>
@stop