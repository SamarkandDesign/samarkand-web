@if ( Session::has('alert') )
@php
$alertClasses = [
'info' =>'bg-blue-800',
'danger' =>'bg-red-800',
'success' =>'bg-green-800',
'warning' =>'bg-orange-800',
];
@endphp
<div class="text-white text-sm font-bold px-4 py-3 clearfix {{ $alertClasses[Session::get('alert-class', 'info')] }}"
	role="alert">
	{{ Session::get('alert') }}
</div>
@endif