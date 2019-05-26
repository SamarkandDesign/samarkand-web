<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" class="not-italic ">
    @if($address->name)
    {{ $address->name }}<br>
    @endif
    <span itemprop="streetAddress">{{ $address->line_1 }}</span><br>

    @if($address->line2)
    <span itemprop="addressLocality">{{ $address->line_2 }}</span><br>
    @endif

    @if($address->line3)
    <span>{{ $address->line_3 }}</span><br>
    @endif

    <span itemprop="addressRegion">{{ $address->city }}</span>
    <span itemprop="postalCode">{{ $address->postcode }}</span><br>
    <span itemprop="addressCountry">{{ $address->country_name }}</span><br><br>
    {{ $address->phone }}
</address>