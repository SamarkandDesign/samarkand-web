<div>
<h3 slot="title">By Category</h3>

<ul class="sidebar-list">
  @foreach($product_categories as $category)
  <li>
    <a
      href="/shop/{{ $category->slug }}"
      class="category-label {{ $product_category->slug === $category->slug ? 'active' : '' }}"
    >
      {{ $category->term }}
    </a>
  </li>
  @endforeach
</ul>
</div>
