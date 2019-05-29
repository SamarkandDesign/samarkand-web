@inject('countries', 'App\Countries\CountryRepository')
<div class="vspace-4 md:w-1/2 mx-auto">
    <div class="form-group {{ $errors->has("name") ? 'has-error' : '' }}">
        <label class="control-label" for="name">Name</label>
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
    </div>

    <div class="form-group {{ $errors->has("line_1") ? 'has-error' : '' }}">
        <label class="control-label" for="line_1">Address</label>
        {!! Form::text('line_1', null, ['class' => 'form-control', 'placeholder' => 'House number/name, street', 'id' =>
        'line_1']) !!}
    </div>

    <div class="flex -mx-2">
        <div class="form-group px-2 w-1/2 {{ $errors->has("line_2") ? 'has-error' : '' }}">
            <label class="control-label" for="line_2" class="sr-only">Line 2</label>
            {!! Form::text('line_2', null, ['class' => 'form-control', 'placeholder' => 'Line 2', 'id' => 'line_2',])
            !!}
        </div>
        <div class="form-group px-2 w-1/2 {{ $errors->has("line_3") ? 'has-error' : '' }}">
            <label class="control-label" for="line_3" class="sr-only">Line 3</label>
            {!! Form::text('line_3', null, ['class' => 'form-control', 'placeholder' => 'Line 3', 'id' => 'line_3',])
            !!}
        </div>
    </div>

    <div class="flex -mx-2">
        <div class="form-group px-2 w-1/2 {{ $errors->has("city") ? 'has-error' : '' }}">
            <label class="control-label" for="city">Town/City/Region</label>
            {!! Form::text('city', null, ['class' => 'form-control', 'id' => 'city']) !!}
        </div>
        <div class="form-group px-2 w-1/2  {{ $errors->has("postcode") ? 'has-error' : '' }}">
            <label class="control-label" for="postcode">Postcode</label>
            {!! Form::text('postcode', null, ['class' => 'form-control', 'id' => 'postcode']) !!}
        </div>
    </div>

    <div class="form-group  {{ $errors->has("country") ? 'has-error' : '' }}">
        <label class="control-label" for="country">Country</label>
        <div class="relative">
            {!! Form::select('country', $countries->pluck(), null, ['class' => 'form-control', 'id' => 'country']) !!}
            @include('partials.select_arrow')
        </div>
    </div>

    <div class="form-group {{ $errors->has("phone") ? 'has-error' : '' }}">
        <label class="control-label" for="phone">Phone number</label>
        {!! Form::tel('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
    </div>
    <button type="submit" class="btn btn-success">{{ isset($submitText) ? $submitText : 'Submit' }}</button>
</div>