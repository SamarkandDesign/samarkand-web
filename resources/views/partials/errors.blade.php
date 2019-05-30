@if (isset($errors) and count($errors) > 0)
<div class="text-white bg-red-800 text-sm font-normal px-4 py-3" role="alert">
	Whoops! There were some problems with your input:<br><br>
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
