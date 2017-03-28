@inject('queryFilter', 'App\Services\AttributeQueryBuilder')

<div>
<h3 slot="title">Filter</h3>

@foreach ($product_attributes as $attribute)
    @if($attribute->attribute_properties->count() > 0)
        <div class="xpanel-body">
        <h4>By {{ $attribute->name }}</h4>
        <ul class="sidebar-list">
        @foreach ($attribute->attribute_properties as $property)
            <?php $queryFilter->setFilters([$attribute->slug => $property->slug]); ?>
            <li>
                <a href="/shop{{ $product_category->id ? "/{$product_category->slug}" : '' }}?{{ $queryFilter->getQueryString() }}">
                    {{ $property->name }}
                </a> ({{ $property->products()->listed()->count() }})
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
