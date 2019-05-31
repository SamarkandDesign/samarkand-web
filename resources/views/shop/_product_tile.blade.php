<a href="{{ $product->url }}">
  <img src="{{ $product->present()->thumbnail_url() }}" alt="{{ $product->name }}">
  <div class="text-center py-2 text-gray-800">
    <div class="product-description">

      <h3>{{ $product->name }}</h3>
    </div>
    <p class="product-grid-price">
      <span class="price">{{ $product->present()->price() }}</span>
    </p>
  </div>
</a>