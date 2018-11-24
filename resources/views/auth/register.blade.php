@extends('layouts.main')

@section('content')

@include('partials.errors')
<div class="row">
<form class="col-sm-6 col-sm-offset-3" role="form" method="POST" action="{{ route('register') }}">
	<h1>Create an account</h1>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class="form-group">
		<label class="control-label">Your name</label>
		<input type="text" class="form-control" name="name" value="{{ old('name') }}">
	</div>


	<div class="form-group">
		<label class="control-label">Email address</label>
		<input type="email" class="form-control" name="email" value="{{ old('email') }}">
	</div>

	<div class="form-group">
		<label class="control-label">Password</label>
		<input type="password" class="form-control" name="password">
	</div>

	<div class="form-group">
		<div class="">
			<button type="submit" class="btn btn-primary">
				Create account
			</button>
		</div>
	</div>
</form>
</div>
@endsection
