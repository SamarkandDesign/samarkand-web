@if ( Session::has('alert') )
<alert type="{{ Session::get('alert-class', 'info') }}">
	{{ Session::get('alert') }}
</alert>
@endif
