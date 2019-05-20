@extends('layouts.gateway')

@section('title')
Forgot Password
@endsection

@section('content')

@if (session('status'))
<alert type="success" dismissible="false">
	{{ session('status') }}
</alert>
@endif


	<h1 class="text-center">Reset Your Password</h1>

	<form role="form" method="POST" action="/password/email"  class="vspace-5">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<div class="form-group">
			<label class="col-md-4 control-label">Email address</label>
			<div class="col-md-6">
				<input type="email" class="form-control" name="email" value="{{ old('email') }}">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" class="btn btn-primary">
					Send password reset link
				</button>
			</div>
		</div>
	</form>


@endsection
