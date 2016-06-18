
@if ($product->media->count() < 2)

<img src="{{ $product->media->count() ? $product->media->first()->getUrl('wide') : 'http://placehold.it/1300x866' }}" alt="%s" style="width:100%;" class="img-responsive">

@else

<carousel>
    @foreach($product->media as $key => $media)
    <slider>
		<img src="{{ $media->getUrl('wide') }}">
	</slider>
    @endforeach
</carousel>

@endif
