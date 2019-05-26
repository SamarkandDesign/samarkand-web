@extends('layouts.main')

@section('title')
Addresses
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
  <li><a href="/account">My Account</a></li>
  <li class="active">Addresses</li>
</ol>

@endsection

@section('content')

@if(!$addresses->count())
<p>You don't have any addresses saved. You can add one below.</p>
@endif
<a href="{{ route('addresses.create') }}" class="btn btn-default">Add New Address</a>

<div class="flex -mx-4">
  @foreach ($addresses as $address)
  <div class="md:w-1/3 px-4 ">
    <div class="border border-gray-400 p-4 rounded">

      @include('partials.address', compact('address'))

      {!! Form::open(['route' => ['addresses.update', $address], 'method' => 'DELETE']) !!}
      <button type="submit" class="btn btn-link"><span class="text-danger"><i class="fa fa-trash"></i>
          Delete</span></button>
      <a href="{{ route('addresses.edit', $address) }}" class="btn btn-link"><i class="fa fa-pencil"></i> Edit</a>
      {!! Form::close() !!}
    </div>
  </div>
  @endforeach
</div>
@stop