@if ($product->media->count() === 1)
<img src="{{ $product->media->first()->getUrl('wide') }}" alt="{{ $product->media->first()->getCustomProperty('title') }}" style="width:100%;" class="img-responsive">
@elseif ($product->media->count() === 0)
<img src="/img/placeholder-landscape.png" alt="Placeholder image" style="width:100%;" class="img-responsive">
@else
<flicker>
  @foreach($product->media as $key => $media)

  <carousel-cell cell-height='auto'>
    <a href="{{ $media->getUrl() }}" class='show' target="_blank">
      <img src="{{ $media->getUrl('wide') }}" alt="{{ $media->getCustomProperty('title') }}" class="img-responsive">
    </a>
  </carousel-cell>
  @endforeach
</flicker>
@endif
