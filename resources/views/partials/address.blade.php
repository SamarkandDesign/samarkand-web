<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
    @if($address->name)
    {{ $address->name }}<br>
    @endif
    <span itemprop="streetAddress">{{ $address->line_1 }}</span><br>
    {!! $address->line_2 ? "<span itemprop=\"addressLocality\">{$address->line_2}</span><br>" : '' !!}
    {!! $address->line_3 ? "{$address->line_3}<br>" : '' !!}
    <span itemprop="addressRegion">{{ $address->city }}</span> <span itemprop="postalCode">{{ $address->postcode }}</span><br>
    <span itemprop="addressCountry">{{ $address->country_name }}</span><br>
    {{ $address->phone }}
</address>
