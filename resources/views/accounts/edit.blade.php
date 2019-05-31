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
    <div class="md:w-1/2 mx-auto vspace-4">
        <h1 class="page-heading">Edit Account</h1>
        <div class="form-group">
            <label class="control-label" for="name">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label class="control-label" for="email">Email</label>
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
        </div>


        <div class="form-group">
            <label class="control-label" for="password">Change password</label>
            {!! Form::password('password', ['class' => 'form-control']) !!}
            @if($user->id)
            <span class="text-xs text-gray-700">(Leave blank to leave unchanged)</span>
            @endif
        </div>

        <div class="form-group">
            <label class="control-label" for="password_confirmation">Confirm password</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>

        <button type="submit" class="btn btn-primary">Update Account</button>
    </div>
</div>
{!! Form::close() !!}
@stop