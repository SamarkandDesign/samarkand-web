<navbar class="navbar navbar-inverse navbar-fixed-top">

  <a slot="brand" class="navbar-brand" href="/" style="padding: 6px 15px;">
    <img src="/img/samarkand-logo-250.svg" alt="Samarkand Logo" width="39" height="39" />
  </a>

  <ul class="nav navbar-nav">
    <li class=""><a href="/">Home</a></li>
    <li><a href="/shop">Shop</a></li>
    <li><a href="/contact">Contact</a></li>
  </ul>

  <ul class="nav navbar-nav navbar-right">
    @include('partials._cart')
    @include('partials._auth')
  </ul>
</navbar>
