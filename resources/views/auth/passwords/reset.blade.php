@extends('layouts.gateway')

@section('title')
Reset Password
@endsection

@section('content')
<h1 class="text-center">Reset Password</h1>


	<form  role="form" method="POST" action="/password/reset"  class="vspace-5">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<div class="form-group">
			<label class="col-md-4 control-label">Email address</label>
			<div class="col-md-6">
				<input type="email" class="form-control" name="email" value="{{ old('email') }}">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Password</label>
			<div class="col-md-6">
				<input type="password" class="form-control" name="password">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Confirm password</label>
			<div class="col-md-6">
				<input type="password" class="form-control" name="password_confirmation">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" class="btn btn-primary">
					Reset password
				</button>
			</div>
		</div>
	</form>
</div>

@endsection
