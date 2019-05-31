@extends('admin.layouts.admin')

@section('title')
{{ 'Bulk Upload Products' }}
@stop

@section('heading')
{{ 'Bulk Upload Products' }}
@stop

@section('admin.content')

@include('admin.partials.errors')

@if ($failures = session('failures'))
<alert type="warning">
  <p>The following imports failed because the data provided failed validation:</p>
  <ul>
    @foreach ($failures as $failure)
    <li>
      <strong>{{ array_get($failure, 'data.name') }}</strong> ({{ array_get($failure, 'data.sku') }})

      <ul>
        @foreach ($failure['errors']->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </li>
    @endforeach
  </ul>
</alert>
@endif

<div class="box box-primary">
  <div class="box-body">



    <form action="/admin/products/upload" method="POST" enctype="multipart/form-data">
      {!! csrf_field() !!}
      <div class="form-group">
        <label for="product-upload">Products</label>
        <input type="file" name="file" id="product-upload">
        <p class="help-block">CSV File</p>
      </div>

      <input type="submit" class="btn btn-primary" value="Import Products">
    </form>

  </div>
</div>

@stop