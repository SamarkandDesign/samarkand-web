@extends('layouts.outer')

@section('body')
  @yield('section-header')

	<div class="h-full bg-gray-200 p-4">
	<div class="my-12 mx-auto w-full md:w-1/2 lg:w-2/5 xl:w-1/3" >
    	@include('partials.alert')
    	@include('partials.errors')
		<div class="p-4 bg-white shadow-md">
    @yield('content')
  </div>

</div>
</div>
@stop
