@extends('layouts.main')

@section('title')
Add Address
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
  <li><a href="/account">My Account</a></li>
  <li><a href="/account/addresses">Addresses</a></li>
  <li class="active">Add Address</li>
</ol>

@endsection

@section('content')

@include('partials.errors')


<h1 class="page-heading">Add New Address</h1>
{!! Form::model($address, ['route' => ['addresses.store'], 'method' => 'POST']) !!}
@include('addresses.form', ['submitText' => 'Create address'])
{!! Form::close() !!}


@stop