<navbar>
  <a slot="image"  href="/">
    <img src="/img/leaf-white.svg" alt="Samarkand Logo" class="h-8 w-8 mr-2" />
  </a>

  @if (isset($menuItems['main']))
  <ul slot="items" class="list-reset lg:flex-grow block">
    @foreach($menuItems['main'] as $item)
    <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
      <a class="no-underline text-grey-darkest hover:text-grey-darker" href="{{ $item->link }}">{{ $item->label }}</a>
    </li>
    @endforeach
  </ul>
  @endif

  <div slot="right-items">
    <ul class="list-reset w-full block flex-grow lg:flex lg:items-center lg:w-auto">
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
        <a href="/cart" class="no-underline text-grey-darkest hover:text-grey-darker">
          <i class="fa fa-shopping-cart"></i>
          {{ config('shop.currency_symbol') }}{{ Cart::total() }} ({{ Cart::count() }} {{ str_plural('Item', Cart::count()) }})
        </a>
      </li>
      @if (Auth::check())
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
          <a href="/account" class="no-underline text-grey-darkest hover:text-grey-darker">My Account</a>
      </li>
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
          <form method="post" action="/logout">
            {!! csrf_field() !!}
            <button type="submit" class="font-light no-underline text-grey-darkest hover:text-grey-darker">Sign out</button>
            </form>
      </li>
      @else
      <li class="block mt-4 lg:inline-block lg:mt-0 mr-4">
          <a href="/login" class="no-underline text-grey-darkest hover:text-grey-darker">Login</a>
      </li>
      @endif
    </ul>
  </div>
</navbar>
