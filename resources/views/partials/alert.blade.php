@if ( Session::has('alert') )
@php
$alertClasses = [
'info' =>'bg-blue-700',
'danger' =>'bg-red-700',
'success' =>'bg-green-700',
'warning' =>'bg-orange-700',
];
@endphp
<div class="text-white text-sm font-bold px-4 py-3 clearfix {{ $alertClasses[Session::get('alert-class', 'info')] }}"
	role="alert">
	{{ Session::get('alert') }}
</div>
@endif