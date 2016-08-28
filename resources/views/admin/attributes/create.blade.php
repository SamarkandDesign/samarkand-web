@extends('admin.layouts.admin')

@section('title')
{{ $title or 'New Attribute' }}
@stop

@section('heading')
{{ $title or 'New Attribute' }}
@stop

@section('admin.content')
<p>
    <a href="{{ route('admin.attributes.index') }}" class="btn btn-link"><i class="fa fa-chevron-left"></i> All Attributes</a>
</p>

<div class="box box-primary">
    <div class="box-body">
      {!! Form::model($product_attribute, ['route' => 'admin.attributes.store', 'method' => 'POST']) !!}

      <div class="col-sm-4 form-group {{ $errors->has("name") ? 'has-error' : '' }}">
          <label for="name">Attribute Name</label>
          {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'e.g. Size, Colour', 'id' => 'name', 'required']) !!}
          {!! $errors->has('name') ? '<span class="help-block">'.$errors->first('name').'</span>' : '' !!}
      </div>

      <div class="col-sm-4 form-group {{ $errors->has("slug") ? 'has-error' : '' }}">
          <label for="slug">Slug (Optional)</label>
          {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug']) !!}
          {!! $errors->has('slug') ? '<span class="help-block">'.$errors->first('slug').'</span>' : '' !!}
      </div>

      <div class="col-sm-4">
        <label class="hidden-xs">&nbsp;</label>
        <button class="btn btn-success btn-block" type="submit">Save Attribute</button>
      </div>
    	{!! Form::close() !!}

    </div>
</div>

@stop
