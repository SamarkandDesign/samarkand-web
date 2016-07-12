@extends('admin.layouts.admin')

@section('title')
{{ $title or 'Attributes' }}
@stop

@section('heading')
{{ $title or 'Attributes' }}
@stop

@section('admin.content')

<p>
    <a href="{{route('admin.attributes.create')}}" class="btn btn-success pull-right">New Attribute</a>
</p>
<p class="clearfix"></p>

<div class="box box-primary">
    <div class="box-body">


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Properties</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($attributes as $attribute)
                <tr>
                    <td>
                        <strong>{{ $attribute->name }}</strong>
                    </td>
                    <td>{{ $attribute->attribute_properties->implode('name', ', ') }}</td>
                    <td style="width:80px;">

                        <a href="{{ route('admin.attributes.edit', $attribute) }}"><i class="fa fa-pencil"></i></a>
                        @include('partials._delete_link', [
                        'url' => route('admin.attributes.delete', $attribute),
                        'link_text' => new Illuminate\Support\HtmlString('<i class="fa fa-fw fa-trash"></i>'),
                        ])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

@stop
