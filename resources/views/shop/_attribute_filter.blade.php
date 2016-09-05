@inject('queryFilter', 'App\Services\AttributeQueryBuilder')

<h3>Filter</h3>
<div class="row">
    @foreach ($product_attributes as $attribute)
      @if($attribute->attribute_properties->count() > 0)
    <div class="col-sm-6">
        <div class="panel panel-default attribute-panel">
            <div class="panel-body">
                <h4>{{ $attribute->name }}</h4>
                <ul class="attribute-ul">

                    @foreach ($attribute->attribute_properties as $property)
                        <?php $queryFilter->setFilters([$attribute->slug => $property->slug]); ?>
                        <li>
                     {{--        <i class="fa {{ $queryFilter->hasCurrentFilter($attribute->slug, $property->slug) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> --}}
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
        </div>
    </div>
  @endif
    @endforeach
</div>
