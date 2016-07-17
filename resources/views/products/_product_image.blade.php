@if ($product->media->count() === 1)
  <img src="{{ $product->media->first()->getUrl('wide') }}" alt="{{ $product->media->first()->getCustomProperty('title') }}" style="width:100%;" class="img-responsive">
@elseif ($product->media->count() === 0)
  <img src="/img/placeholder-landscape.png" alt="Placeholder image" style="width:100%;" class="img-responsive">
@else
  <carousel>
    @foreach($product->media as $key => $media)
      <slider>
        <img src="{{ $media->getUrl('wide') }}" alt="{{ $media->getCustomProperty('title') }}">
      </slider>
    @endforeach
  </carousel>
@endif
