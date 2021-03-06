<div class="vspace-4">
    <div class="form-group {{ $errors->has("address.{$type}.name") ? 'has-error' : '' }}">
        <label class="control-label" for="{{ $type }}_address_name">Name</label>
        <input id="{{ $type }}_address_name" type="text" name="address[{{ $type }}][name]"
            value="{{ $address['name'] }}" class="form-control"
            placeholder="{{ $type == 'billing' ? 'As it appears on card' : '' }}">
    </div>

    <div class="form-group {{ $errors->has("address.{$type}.line_1") ? 'has-error' : '' }}">
        <label class="control-label" for="{{ $type }}_address_line_1">Address</label>
        <input type="text" name="address[{{ $type }}][line_1]" value="{{ $address['line_1'] }}" class="form-control"
            id="{{ $type }}_address_line_1" placeholder="Street address, house name/number" />
    </div>

    <div class="flex -mx-2">
        <div class="form-group  px-2 w-1/2">
            <label class="sr-only" for="{{ $type }}_address_line_2">Line 2</label>
            <input type="text" name="address[{{ $type }}][line_2]" value="{{ $address['line_2'] }}" class="form-control"
                id="{{ $type }}_address_line_2" placeholder="Line 2" />
        </div>

        <div class="form-group  px-2 w-1/2">
            <label class="sr-only" for="{{ $type }}_address_line_3">Line 3</label>
            <input type="text" name="address[{{ $type }}][line_3]" value="{{ $address['line_3'] }}" class="form-control"
                id="{{ $type }}_address_line_3" placeholder="Line 3" />
        </div>
    </div>

    <div class="flex -mx-2">
        <div class="form-group px-2 w-1/2 {{ $errors->has("address.{$type}.city") ? 'has-error' : '' }}">
            <label class="control-label" for="{{ $type }}_address_city">Town/City</label>
            <input type="text" name="address[{{ $type }}][city]" value="{{ $address['city'] }}" class="form-control"
                id="{{ $type }}_address_city" />
        </div>

        <div class="form-group px-2 w-1/2{{ $errors->has("address.{$type}.postcode") ? 'has-error' : '' }}">
            <label class="control-label" for="{{ $type }}_address_postcode">Postcode</label>
            <input type="text" name="address[{{ $type }}][postcode]" value="{{ $address['postcode'] }}"
                class="form-control" id="{{ $type }}_address_postcode" placeholder="Postcode, zip" />
        </div>
    </div>

    <div class="form-group {{ $errors->has("address.{$type}.country") ? 'has-error' : '' }}">
        <label class="control-label" for="{{ $type }}_address_country">Country</label>
        <div class="relative">

            {!! Form::select("address[$type][country]", $countries->pluck(), $address['country'], [
            'class' => 'form-control',
            'id' => "{$type}_address_country",
            ]) !!}
            @include('partials.select_arrow')
        </div>
    </div>

    <div class="form-group {{ $errors->has("address.{$type}.phone") ? 'has-error' : '' }}">
        <label class="control-label" for="{{ $type }}_address_phone">Phone number</label>
        <input type="tel" name="address[{{ $type }}][phone]" value="{{ $address['phone'] }}" class="form-control"
            id="{{ $type }}_address_phone" />
    </div>

</div>