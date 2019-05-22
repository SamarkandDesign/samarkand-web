@extends('layouts.main')

@section('title')
@if ($product_category->id)
{{ $product_category->term }} |
@elseif (Request::has('query'))
"{{ Request::get('query') }}" |
@endif
Shop
@endsection

@section('head')
@if ($product_category->id)
<meta name="description" content="Shop {{ $product_category->term }} sourced from around the world">
@else
<meta name="description" content="Shop vintage lighting, lampshades and home accessories">
@endif
@endsection

@section('section-header')
@if($product_category->id)
<header class="h-56 flex items-end" style="background-image: url('/img/product-cat-bg.jpg'); background-size: cover;">
  <div class="container mx-auto p-4">
    <h1 class="text-white text-5xl font-serif italic ">{{ $product_category->term }}</h1>
  </div>
</header>
@endif
@endsection

@section('breadcrumb')
@if ($product_category->id or 'uncategorised' == $product_category->slug)
<ol class="list-none list-reset">
  <li class="inline-block"><a href="/shop">Shop</a></li>
  <li class="inline-block">{{ $product_category->term }}</li>
</ol>
@endif
@endsection

@section('content')

<div class="row">
  <section class="products col-sm-9 col-sm-push-3" v-pre>
    @if(Request::has('query'))
    <h2>Results for "{{ Request::get('query') }}"</h2>
    @elseif($product_category->id)
    <h2>{{ $product_category->term }}</h2>
    @endif

    @if($products->total() > 0)
    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} items</p>
    @else
    No items
    @endif

    @foreach ($products->chunk($products_per_row) as $product_group)
    <div class="flex flex-wrap -mx-2">

      @foreach ($product_group as $i => $product)
      <div class="flex-shrink-0 mt-4 w-1/2 sm:w-1/4 px-2">
        @include('shop._product_tile', compact('product'))
      </div>
      @endforeach

    </div>
    @endforeach

    <div class="text-center">
      {!! $products->appends(Request::query())->links() !!}
    </div>
  </section>

  <aside class="shop-filters col-sm-3 col-sm-pull-9">
    <div class="xcol-md-6">
      @include('shop._category_filter')
      @include('shop._attribute_filter')
    </div>
  </aside>
</div>


@stop