@inject('queryFilter', 'App\Services\AttributeQueryBuilder')

<div class="vspace-2">
    <h3 class="text-xl">Filter</h3>

    @foreach ($product_attributes as $attribute)
    @if($attribute->attribute_properties->count() > 0)
    <div>
        <h4>By {{ $attribute->name }}</h4>
        <ul class="text-sm">
            @foreach ($attribute->attribute_properties as $property)
            <?php $queryFilter->setFilters([$attribute->slug => $property->slug]); ?>
            <li>
                <a
                    href="/shop{{ $product_category->id ? "/{$product_category->slug}" : '' }}?{{ $queryFilter->getQueryString() }}">
                    {{ $property->name }}
                </a> ({{ $property->products()->listed()->inStock()->count() }})
                @if ($queryFilter->hasCurrentFilter($attribute->slug, $property->slug))
                <span class="pull-right"><i class="fa fa-check"></i></span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    @endforeach

</div>