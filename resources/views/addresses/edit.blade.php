@extends('layouts.main')

@section('title')
Edit Address
@endsection

@section('content')

<ol class="breadcrumb">
  <li><a href="/account">My account</a></li>
  <li><a href="/account/addresses">Addresses</a></li>
  <li class="active">Edit address</li>
</ol>

@include('partials.errors')

<div class="col-md-offset-3 col-md-6">
<h1>Edit Address</h1>
{!! Form::model($address, ['route' => ['addresses.update', $address->id], 'method' => 'PATCH']) !!}
    @include('addresses.form')
    <input type="submit" class="btn btn-success" value="Update Address">
{!! Form::close() !!}
</div>

@stop
