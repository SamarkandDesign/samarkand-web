<li>
<a href="/cart" class="highlight">
<i class="fa fa-shopping-cart"></i>
{{ config('shop.currency_symbol') }}{{ Cart::total() }} ({{ Cart::count() }} {{ str_plural('Item', Cart::count()) }})
</a>
</li>
