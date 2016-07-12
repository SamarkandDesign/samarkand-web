<div class="box box-primary">
  <div class="box-header">
    Attributes
  </div>
  <div class="box-body">
    <div class="box-group" id="attributes">
      @foreach ($product_attributes as $attribute)
        <div class="panel box box-default">
          <div class="box-header">
            <a data-toggle="collapse" data-parent="#attributes" href="#attribute-{{ str_slug($attribute->slug) }}" aria-expanded="true">
              {{ $attribute->name }}
            </a>

          </div>
          <div class="panel-collapse collapse" id="attribute-{{ str_slug($attribute->slug) }}">
            @foreach ($attribute->attribute_properties as $property)
              <div class="checkbox">
                <label>
                  {{ Form::checkbox('attributes[]', $property->id, $product->attribute_properties->pluck('id')->contains($property->id)) }}
                  {{ $property->name }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
    <div class="box-group">
      <p class="top-buffer">
        <a href="{{ route('admin.attributes.index') }}" class="btn btn-default btn-small">Manage Product Attributes</a>
      </p>
    </div>
  </div>
</div>
