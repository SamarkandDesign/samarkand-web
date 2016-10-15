<collapser>
<span slot="title">Categories</span>

<ul class="term-list">
@foreach($product_categories as $category)
<li>
  <a href="/shop/{{ $category->slug }}" class="category-label {{ $product_category->slug === $category->slug ? 'active' : '' }}">
      {{ $category->term }}
  </a>
</li>
@endforeach
</ul>
</collapser>