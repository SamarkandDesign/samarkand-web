@if ( Session::has('alert') )
<alert type="{{ Session::get('alert-class', 'info') }}" block="true">
	{{ Session::get('alert') }}
</alert>
@endif
