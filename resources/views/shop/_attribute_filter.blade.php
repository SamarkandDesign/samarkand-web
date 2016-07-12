@inject('queryFilter', 'App\Services\AttributeQueryBuilder')

<h3>Filter</h3>
<div class="row">
    @foreach ($product_attributes as $attribute)
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>{{ $attribute->name }}</h4>
                <ul class="fa-ul">

                    @foreach ($attribute->attribute_properties as $property)
                        <?php $queryFilter->setFilters([$attribute->slug => $property->slug]); ?>
                        <li>
                            <i class="fa-li fa {{ $queryFilter->hasCurrentFilter($attribute->slug, $property->slug) ? 'fa-check-square-o' : 'fa-square-o' }}"></i>
                            <a href="/shop{{ $product_category->id ? "/{$product_category->slug}" : '' }}?{{ $queryFilter->getQueryString() }}">
                                {{ $property->name }}
                            </a> ({{ $property->products()->count() }})
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>
