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
<header class="h-56 flex items-end bg-cover" style="background-image: url('/img/product-cat-bg.jpg');">
  <div class="container mx-auto p-4">
    <h1 class="text-white text-5xl font-serif italic ">{{ $product_category->term }}</h1>
  </div>
</header>
@endif
@endsection

@section('breadcrumb')
@if ($product_category->id or 'uncategorised' == $product_category->slug)
<ol class="breadcrumb">
  <li><a href="/shop">Shop</a></li>
  <li>{{ $product_category->term }}</li>
</ol>
@endif
@endsection

@section('content')

<div class="block sm:flex flex-row-reverse -mx-4">
  <section class="px-4 w-full sm:w-4/5" v-pre>
    @if(Request::has('query'))
    <h2>Results for "{{ Request::get('query') }}"</h2>
    @endif

    @if($products->total() > 0)
    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} items</p>
    @else
    No items
    @endif

    @foreach ($products->chunk($products_per_row) as $product_group)
    <div class="flex flex-wrap -mx-4">

      @foreach ($product_group as $i => $product)
      <div class="flex-shrink-0 mt-10 w-1/2 md:w-1/4 px-4">
        @include('shop._product_tile', compact('product'))
      </div>
      @endforeach

    </div>
    @endforeach

    <div class="mt-8">
      {!! $products->appends(Request::query())->links() !!}
    </div>
  </section>

  <aside class="flex-1 flex sm:block">
    <div class="w-1/2 sm:w-full px-4 mb-5">
      @include('shop._category_filter')
    </div>
    <div class="w-1/2 sm:w-full px-4 mb-5">
      @include('shop._attribute_filter')
    </div>

  </aside>
</div>


@stop