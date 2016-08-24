
		{!! Form::hidden('id') !!}
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="name">Name</label>
					{!! Form::text('name', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
					<label for="username">Username</label>
					{!! Form::text('username', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
					<label for="email">Email</label>
					{!! Form::email('email', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
					<label for="telegram_id">Telegram ID <span class="help-text small text-muted">(For notifications)</span></label>
					<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-at"></i></span>
					{!! Form::text('telegram_id', null, ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			<div class="col-md-6">

				<div class="form-group">
					<label for="password">Password</label>
					@if($user->id)
					<span class="help-text small text-muted">(Leave blank to leave unchanged)</span>
					@endif
					{!! Form::password('password', ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
					<label for="password_confirmation">Confirm Password</label>
					{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
				</div>

				<div class="form-group ">
					<label for="roles">Role</label>
					{!! Form::select('role_id', $roles, $user->role_id, ['class' => 'form-control']) !!}
				</div>

				<div class="checkbox">
					<input type="hidden" name="is_shop_manager" value="0">
					<label>
						{!! Form::checkbox('is_shop_manager', '1') !!} Shop Manager <span class="help-text small text-muted">(Notify for new orders, low stock)</span>
					</label>
				</div>
			</div>
		</div>

		<button class="btn btn-success">{{ $submit_text or 'Submit' }}</button>
