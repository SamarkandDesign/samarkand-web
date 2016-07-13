<li>
<a href="/cart" class="highlight">
<i class="fa fa-shopping-cart"></i>
{{ Present::money(Cart::total()) }} ({{ Cart::count() }} {{ str_plural('Item', Cart::count()) }})
</a>
</li>
