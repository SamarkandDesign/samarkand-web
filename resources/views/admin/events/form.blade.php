@inject('countries', 'App\Countries\CountryRepository')

<div class="col-md-8">
	<div class="panel panel-default" id="postContent">
		<div class="panel-body">
			<input type="text" class="hidden" name="title" value="{{ old('title') }}">
			<cr-title-slugger value="{{ $event->title }}" slug="{{ $event->slug }}" name="title"></cr-title-slugger>

			<div class="form-group top-buffer" id="postContent">
				<input type="text" name="description" class="hidden">
				<cr-markarea value="{{ $event->description }}" name="description" title="Content"></cr-markarea>

			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="checkbox">
						<input type="hidden" name="create_new_venue" value="0">
						<label>
							{!! Form::checkbox('create_new_venue', '1') !!} Create new  Venue
						</label>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('address_id', 'Venue') !!}
						{!! Form::select('address_id', App\Address::pluck('line_1', 'id'), null, ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has("address.line_1") ? 'has-error' : '' }}">
						<label for="line_1">Address</label>
						{!! Form::text('address[line_1]', null, ['class' => 'form-control', 'placeholder' => 'House number/name, street', 'id' => 'line_1']) !!}
					</div>

					<div class="form-group {{ $errors->has("address.line_2") ? 'has-error' : '' }}">
						<label for="line_2" class="sr-only">Line 2</label>
						{!! Form::text('address[line_2]', null, ['class' => 'form-control', 'placeholder' => 'Line 2', 'id' => 'line_2']) !!}
					</div>

					<div class="form-group {{ $errors->has("address.line_3") ? 'has-error' : '' }}">
						<label for="line_3" class="sr-only">Line 3</label>
						{!! Form::text('address[line_3]', null, ['class' => 'form-control', 'placeholder' => 'Line 3', 'id' => 'line_3']) !!}
					</div>

					<div class="form-group {{ $errors->has("address.city") ? 'has-error' : '' }}">
						<label for="city">Town/City/Region</label>
						{!! Form::text('address[city]', null, ['class' => 'form-control', 'id' => 'city']) !!}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group {{ $errors->has("address.postcode") ? 'has-error' : '' }}">
						<label for="postcode">Postcode</label>
						{!! Form::text('address[postcode]', null, ['class' => 'form-control', 'id' => 'postcode']) !!}
					</div>

					<div class="form-group {{ $errors->has("address.country") ? 'has-error' : '' }}">
						<label for="country">Country</label>
						{!! Form::select('address[country]', $countries->pluck(), null, ['class' => 'form-control', 'id' => 'country']) !!}
					</div>

					<div class="form-group {{ $errors->has("address.phone") ? 'has-error' : '' }}">
						<label for="phone">Phone Number</label>
						{!! Form::tel('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-md-4"  id="postMeta">
	<div class="box box-primary">

		<div class="box-body">
			<div class="checkbox">
				<input type="hidden" name="all_day" value="0">
				<label>
					{!! Form::checkbox('all_day', '1') !!} All Day Event
				</label>
			</div>

			<div class="form-group">
				{!! Form::label('start_date', 'Event Start') !!}
				<div class="date">
					{!! Form::input('datetime-local', 'start_date', 
					$event->start_date instanceOf DateTime ? $event->start_date->format('Y-m-d\TH:i:s') : date('Y-m-d\TH:i:s'), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('end_date', 'Event End') !!}
				<div class="date">
					{!! Form::input('datetime-local', 'end_date', $event->end_date instanceOf DateTime ? $event->end_date->format('Y-m-d\TH:i:s') : date('Y-m-d\TH:i:s'), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('website', 'Website') !!}
				{!! Form::text('website', null, ['class' => 'form-control']) !!}
			</div>

			{!! Form::submit('Submit', ['class' => 'btn btn-success btn-block']) !!}

		</div>
	</div>
</div>