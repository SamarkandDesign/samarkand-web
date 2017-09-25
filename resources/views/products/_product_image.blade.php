@if ($product->media->count() === 1)
  <img src="{{ $product->media->first()->getUrl('wide') }}" alt="{{ $product->media->first()->getCustomProperty('title') }}" style="width:100%;" class="img-responsive">
@elseif ($product->media->count() === 0)
  <img src="/img/placeholder-landscape.png" alt="Placeholder image" cstyle="width:100%;" class="img-responsive">
@else
  <flicker>
    @foreach($product->media as $key => $media)

  <carousel-cell image='{{ $media->getUrl('wide') }}' :style="{
    paddingTop: '66.6%'
  }">
  </carousel-cell>
    @endforeach
  </flicker>
@endif
