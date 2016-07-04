@inject('countries', 'App\Countries\CountryRepository')

<div class="form-group {{ $errors->has("name") ? 'has-error' : '' }}">
    <label for="name">Name</label>
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
</div>

<div class="form-group {{ $errors->has("line_1") ? 'has-error' : '' }}">
    <label for="line_1">Address</label>
    {!! Form::text('line_1', null, ['class' => 'form-control', 'placeholder' => 'House number/name, street', 'id' => 'line_1']) !!}
</div>

<div class="form-group {{ $errors->has("line_2") ? 'has-error' : '' }}">
    <label for="line_2" class="sr-only">Line 2</label>
    {!! Form::text('line_2', null, ['class' => 'form-control', 'placeholder' => 'Line 2',]) !!}
</div>

<div class="form-group {{ $errors->has("line_3") ? 'has-error' : '' }}">
    <label for="line_3" class="sr-only">Line 3</label>
    {!! Form::text('line_3', null, ['class' => 'form-control', 'placeholder' => 'Line 3',]) !!}
</div>

<div class="form-group {{ $errors->has("city") ? 'has-error' : '' }}">
    <label for="city">Town/City/Region</label>
    {!! Form::text('city', null, ['class' => 'form-control', 'id' => 'city']) !!}
</div>

<div class="form-group {{ $errors->has("postcode") ? 'has-error' : '' }}">
    <label for="postcode">Postcode</label>
    {!! Form::text('postcode', null, ['class' => 'form-control', 'id' => 'postcode']) !!}
</div>

<div class="form-group {{ $errors->has("country") ? 'has-error' : '' }}">
    <label for="country">Country</label>
    {!! Form::select('country', $countries->lists(), null, ['class' => 'form-control', 'id' => 'country']) !!}
</div>

<div class="form-group {{ $errors->has("phone") ? 'has-error' : '' }}">
    <label for="phone">Phone Number</label>
    {!! Form::tel('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
</div>
