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

<h1 class="page-heading">Edit Address</h1>
{!! Form::model($address, ['route' => ['addresses.update', $address->id], 'method' => 'PATCH']) !!}
@include('addresses.form', ['submitText' => 'Save address'])

{!! Form::close() !!}

@stop