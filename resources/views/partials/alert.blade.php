@if ( Session::has('alert') )
@php
$alertClasses = [
'info' =>'bg-blue-500',
'danger' =>'bg-red-500',
'success' =>'bg-green-500',
'warning' =>'bg-orange-500',
];
@endphp
<div
	class="text-white text-sm font-bold px-4 py-3 mb-4 clearfix {{ $alertClasses[Session::get('alert-class', 'info')] }}"
	role="alert">
	{{ Session::get('alert') }}
</div>
@endif