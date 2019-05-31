<navbar>
  <a slot="image" href="/">
    <img src="/img/leaf-white.svg" alt="Samarkand Logo" class="h-8 w-8 mr-4" />
  </a>

  @if (isset($menuItems['main']))
  <ul slot="items" class="list-reset lg:flex-grow block">
    @foreach($menuItems['main'] as $item)
    <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
      <a class="no-underline text-gray-900 hover:text-gray-700" href="{{ $item->link }}">{{ $item->label }}</a>
    </li>
    @endforeach
  </ul>
  @endif

  <div slot="search" class="mx-4 md:mx-8 flex-1">
    @include('shop._product_search')
  </div>

  <div slot="right-items">
    <ul class="list-reset w-full block flex-grow lg:flex lg:items-center lg:w-auto">
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
        <a href="/cart" class="no-underline text-gray-900 hover:text-gray-700">
          <i class="fa fa-shopping-cart"></i>
          {{ config('shop.currency_symbol') }}{{ Cart::total() }} ({{ Cart::count() }}
          {{ str_plural('Item', Cart::count()) }})
        </a>
      </li>
      @if (Auth::check())
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
        <a href="/account" class="no-underline text-gray-900 hover:text-gray-700">My Account</a>
      </li>
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
        <form method="post" action="/logout">
          {!! csrf_field() !!}
          <button type="submit" class="uppercase no-underline text-gray-900 hover:text-gray-700">Sign
            out</button>
        </form>
      </li>
      @else
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
        <a href="/login" class="no-underline text-gray-900 hover:text-gray-700">Login</a>
      </li>
      @endif
    </ul>
  </div>
</navbar>