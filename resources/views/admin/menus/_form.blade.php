
    <div class="row">
      <div class="col-sm-6 col-md-3 form-group {{ $errors->has('menu') ? 'has-error' : '' }}">
        {!! Form::label('menu') !!}
        {!! Form::text('menu', null, ['class' => 'form-control input-sm', 'list' => 'menus']) !!}
        <datalist id="menus">
          @foreach ($menus->keys() as $menuName)
          <option value="{{ $menuName }}">
          @endforeach
        </datalist>
      </div>

      <div class="col-sm-6 col-md-3 form-group {{ $errors->has('label') ? 'has-error' : '' }}">
        {!! Form::label('label') !!}
        {!! Form::text('label', null, ['class' => 'form-control input-sm']) !!}
      </div>
      <div class="col-sm-6 col-md-3 form-group {{ $errors->has('link') ? 'has-error' : '' }}">
        {!! Form::label('link') !!}
        {!! Form::text('link', null, ['class' => 'form-control input-sm']) !!}
      </div>
      <div class="col-sm-6 col-md-3 form-group {{ $errors->has('label') ? 'has-error' : '' }}">
        {!! Form::label('order') !!}
        {!! Form::number('order', null, ['class' => 'form-control input-sm']) !!}
      </div>
    </div>
