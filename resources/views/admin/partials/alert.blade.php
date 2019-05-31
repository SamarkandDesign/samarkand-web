@if ( Session::has('alert') )

<div class="alert alert-{{ Session::get('alert-class', 'info') }}" role="alert">
  {{ Session::get('alert') }}
</div>
@endif