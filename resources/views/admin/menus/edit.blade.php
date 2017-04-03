@extends('admin.layouts.admin')

@section('title')
Edit Menu
@stop

@section('heading')
{{ 'Edit Menu' }}
@stop

@section('admin.content')

<div class="box box-primary">
  <div class="box-header">
    Edit Item
  </div>
  <div class="box-body">
    {!! Form::model($menuItem, ['route' => ['admin.menus.update', $menuItem], 'method' => 'PATCH']) !!}
      @include('admin.menus._form')
      <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa fa-check"></i> Update
      </button>
    {!! Form::close() !!}
  </div>
</div>

@stop
