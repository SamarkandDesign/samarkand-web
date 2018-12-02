@if (isset($errors) and count($errors) > 0)
<alert type="danger" :block="true">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
</alert>
@endif
