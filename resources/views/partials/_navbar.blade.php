<navbar class="navbar navbar-inverse navbar-fixed-top">

  <a slot="brand" class="navbar-brand" href="/" style="padding: 6px 15px;">
    <img src="/img/leaf-white.svg" alt="Samarkand Logo" width="auto" height="39" />
  </a>

  <ul class="nav navbar-nav">
    <li class=""><a href="/">Home</a></li>
    <li><a href="/shop">Shop</a></li>
    <li><a href="/events">Events</a></li>
    <li><a href="/faqs">FAQs</a></li>
    <li><a href="/contact">Contact</a></li>
  </ul>

  <ul class="nav navbar-nav navbar-right">
    @include('partials._cart')
    @include('partials._auth')
  </ul>
</navbar>
