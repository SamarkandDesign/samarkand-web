@extends('layouts.main')

@section('title')
Account Settings
@endsection

@section('content')

<ol class="breadcrumb">
  <li><a href="/account">My Account</a></li>
  <li class="active">Edit Account</li>
</ol>

    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['accounts.update', $user] ]) !!}
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1>Edit Account</h1>
                <div class="form-group">
                    <label for="name">Name</label>
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                </div>


                <div class="form-group">
                    <label for="password">Password</label>
                    @if($user->id)
                    <span class="help-text small text-muted">(Leave blank to leave unchanged)</span>
                    @endif
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>

                <button type="submit" class="btn btn-primary">Update Account</button>
            </div>
        </div>
    {!! Form::close() !!}
@stop
