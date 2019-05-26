<div>
  <h3 class="text-xl mb-2">By Category</h3>

  <ul class="sidebar-list text-sm">
    @foreach($product_categories as $category)
    <li>
      <a href="/shop/{{ $category->slug }}"
        class="category-label {{ $product_category->slug === $category->slug ? 'active' : '' }}">
        {{ $category->term }}
      </a>
    </li>
    @endforeach
  </ul>
</div>