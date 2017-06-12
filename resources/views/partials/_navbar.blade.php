<navbar class="navbar navbar-inverse navbar-fixed-top">

  <a slot="brand" class="navbar-brand" href="/" style="padding: 6px 15px;">
    <img src="/img/leaf-white.svg" alt="Samarkand Logo" width="auto" height="39" />
  </a>

  <ul class="nav navbar-nav">
    @if (isset($menuItems['main']))
      @foreach($menuItems['main'] as $item)
        <li><a href="{{ $item->link }}">{{ $item->label }}</a></li>
      @endforeach
    @endif
  </ul>

  <ul class="nav navbar-nav navbar-right">
    @include('partials._cart')
    @include('partials._auth')
  </ul>
</navbar>
