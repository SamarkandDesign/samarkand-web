@extends('layouts.main')

@section('title')
Contact Us
@endsection

@section('content')

  <h1>Contact Us</h1>


  <div class="row">
    <div class="col-sm-8">
      @include('partials.alerts._errors_block')
      {!! Form::model(App\Contact::class, ['route' => 'contacts.store', 'method' => 'post', 'class' => 'top-buffer']) !!}

      <div class="row">

        <div class="form-group col-md-6 {{ $errors->has('name') ? 'has-error' : '' }}">
          <label class="control-label" for="name">Your Name</label>
          {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-md-6 {{ $errors->has('email') ? 'has-error' : '' }}">
          <label class="control-label" for="email">Your Email</label>
          {!! Form::email('email', null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
        <label class="control-label" for="subject">Subject</label>
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
      </div>

      <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
        <label class="control-label" for="message">Message</label>
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
      </div>

      <button type="submit" class="btn btn-primary" id="send">Send Message</button>

      {!! Form::close() !!}
    </div>

    <div class="col-sm-4">
      <hr class="visible-xs">
      <ul class="fa-ul">
        <li>
          <i class="fa-li fa fa-home"></i>Higher Queen Dart,<br>
          Rackenford,<br>
          Tiverton,<br>
          Devon EX16 8EA
        </li>
        <li><i class="fa-li fa fa-envelope"></i>info@sam<span class="hidden">lpd</span>arkanddesign.com</li>
        <li><i class="fa-li fa fa-twitter"></i><a title="Samarkand Design Twitter" href="http://twitter.com/samarkanddesign" target="_blank">@SamarkandDesign</a></li>
        <li><i class="fa-li fa fa-facebook-official"></i><a title="Samarkand Design Facebook" href="https://www.facebook.com/samarkanddesign" target="_blank">Samarkand Design</a></li>
      </ul>
    </div>
  </div>

@stop
