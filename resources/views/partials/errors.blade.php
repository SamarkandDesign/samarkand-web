@if (isset($errors) and count($errors) > 0)
	<div class="text-white bg-red-500 text-sm font-bold px-4 py-3 mb-4" role="alert">
		Whoops! There were some problems with your input:<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
