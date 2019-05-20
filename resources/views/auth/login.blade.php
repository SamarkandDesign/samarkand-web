@extends('layouts.gateway')

@section('title')
Login
@endsection

@section('content')


<form role="form" method="POST" action="{{ route('login') }}" class="vspace-5">
		<h2>Login</h2>
		<input type="hidden" class="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label class="control-label" for="email">Email address</label>
						<input type="email" class="form-control" name="email" id="email" value="{{ old('email', Request::get('email')) }}">
					</div>

					<div class="form-group">
						<label class="control-label" for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password">
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember"> Remember me
							</label>
						</div>
					</div>

					<div class="form-group">

						<button type="submit" class="btn btn-primary" style="margin-right: 15px;">
							Login
						</button>

						<a href="/password/reset">Don't know your password?</a>
				</div>
			</form>


		{{-- <div class="col-sm-6">
			<h2>Create an account</h2>
			<form role="form" method="POST" action="{{ route('register') }}">
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
		</div> --}}


@endsection
