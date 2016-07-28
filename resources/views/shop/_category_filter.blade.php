<h3>Categories</h3>

<ul class="term-list">
@foreach($product_categories as $category)
<li>
  <a href="/shop/{{ $category->slug }}" class="category-label {{ $product_category->slug === $category->slug ? 'active' : '' }}">
      {{ $category->term }}
  </a>
</li>
@endforeach
</ul>
