<address>
    @if($address->name)
    {{ $address->name }}<br>
    @endif
    {{ $address->line_1 }}<br>
    {!! $address->line_2 ? "{$address->line_2}<br>" : '' !!}
    {!! $address->line_3 ? "{$address->line_3}<br>" : '' !!}
    {{ $address->city }} {{ $address->postcode }}<br>
    {{ $address->country_name }}<br>
    {{ $address->phone }}
</address>
