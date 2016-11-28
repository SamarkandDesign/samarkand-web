@extends('admin.layouts.admin')

@section('title')
{{ 'Bulk Upload Products' }}
@stop

@section('heading')
{{ 'Bulk Upload Products' }}
@stop

@section('admin.content')

@include('partials.errors')

<form action="/admin/products/upload" method="POST" enctype="multipart/form-data">
{!! csrf_field() !!}
<div class="form-group">
    <label for="product-upload">Products</label>
    <input type="file" name="file" id="product-upload">
    <p class="help-block">CSV File</p>
</div>

<input type="submit" class="btn btn-primary" value="Import Products">
</form>

@stop