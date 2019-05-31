@extends('layouts.gateway')

@section('title')
Login
@endsection

@section('content')
<div>

	<choice-selector question="Do you have a password?"
		v-bind:choices="[{name: 'signin', label: 'Yes, I\'ve been here before'}, {name: 'register', label: 'No, I\'m a new customer'}]">
		<div slot="signin">
			<form role="form" method="POST" action="{{ route('login') }}" class="vspace-5 mt-5">
				<h2 class="text-xl">Sign in</h2>
				<input type="hidden" class="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group">
					<label class="control-label" for="email">Email address</label>
					<input type="email" class="form-control" name="email" id="email"
						value="{{ old('email', Request::get('email')) }}">
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
						Sign in
					</button>

					<a href="/password/reset">Forgot your password?</a>
				</div>
			</form>
		</div>

		<div slot="register">
			<form role="form" method="POST" action="{{ route('register') }}" class="vspace-5 mt-5">
				<h2 class="text-xl">Create an account</h2>
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
					<label class="control-label">Create password</label>
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
	</choice-selector>
</div>





@endsection