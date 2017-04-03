@extends('admin.layouts.admin')

@section('title')
Menus
@stop

@section('heading')
{{ 'Menus' }}
@stop

@section('admin.content')

<div class="box box-primary">
  <div class="box-header">
    Add a new item
  </div>
  <div class="box-body">
    {!! Form::model($menuItem, ['route' => 'admin.menus.store', 'method' => 'POST']) !!}
      @include('admin.menus._form')
    {!! Form::close() !!}
  </div>
</div>

      @foreach($menus as $menu => $items)
      <div class="box box-primary">
        <div class="box-header">
          {{ $menu }}
        </div>
        <div class="box-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Label</th>
                <th>Link</th>
                <th>Order</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
              <tr>
                <td>{{ $item->label }}</td>
                <td>{{ $item->link }}</td>
                <td>{{ $item->order }}</td>
                <td style="width:80px;">
                  <a href="/admin/menus/{{ $item->id }}/edit"><i class="fa fa-pencil"></i></a>
                  {!! Form::open([
                    'route' => ['admin.menus.delete', $item],
                    'method' => 'DELETE',
                    'style' => 'display: inline',
                  ]) !!}
                    <button type="submit" class="btn btn-link"><span class="text-danger"><i class="fa fa-fw fa-trash"></i></span></button>
                  {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      @endforeach

      @stop
