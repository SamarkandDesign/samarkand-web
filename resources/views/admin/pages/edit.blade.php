@extends('admin.layouts.admin')

@section('title')
Edit Page
@stop

@section('heading')
Edit Page
@stop

@section('admin.content')

@include('admin.partials.errors')

<div class="post-form" id="postForm">
    <div class="row">

        {!! Form::model($page, ['route' => ['admin.pages.update', $page], 'method' => 'PATCH']) !!}

        @include('admin.pages.form', ['submitText' => 'Update'])

        {!! Form::close() !!}


    </div>
    <div class="row">
        <div class="col-md-8 col-md-pul-4">
            @include('admin.media._media_adder', ['model' => $page])

        </div>
    </div>

</div>

@stop