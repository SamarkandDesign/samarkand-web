@extends('layouts.main')

@section('title')
Cart
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="/shop">Shop</a></li>
    <li class="active">Cart</li>
</ol>
@endsection

@section('content')

<h1 class="page-heading">Shopping Basket</h1>
<div class="row">

    <div class="col-sm-8 col-md-9">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-300">
                    <th></th>
                    <th class="p-2 text-left"></th>
                    <th class="p-2 text-left">Product</th>
                    <th class="p-2 text-right">Unit Price</th>
                    <th class="p-2 text-right">Quantity</th>
                    <th class="py-2 pl-2 text-right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach (Cart::content() as $item)
                <tr class="border-b border-gray-300">
                    <td class="py-2 pr-2 text-left">
                        <form action="{{ route('cart.remove', $item->rowId) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="submit" class="btn btn-link text-red-800" name="remove"><i
                                    class="fa fa-times "></i></button>
                        </form>
                    </td>
                    <td class="td-thumbnail">{{ $item->model->present()->thumbnail(45) }}</td>
                    <td class="p-2 text-left"><a href="{{ $item->model->url }}">{{ $item->name }}</a></td>
                    <td class="p-2 text-right">{{ $item->model->present()->price() }}</td>
                    <td class="p-2 text-right">{{ $item->qty }}</td>
                    <td class="py-2 pl-2 text-right">
                        {{ Present::money($item->model->getPrice()->asDecimal() * $item->qty) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5"></th>
                    <th class="py-2 pl-2 text-right">{{ config('shop.currency_symbol') }}{{ Cart::total() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-sm-4 col-md-3">
        <a href="/checkout" class="btn btn-success btn-block btn-lg">Proceed to checkout</a>

    </div>

</div>

@stop