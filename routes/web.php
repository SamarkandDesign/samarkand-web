<?php

Route::get('/', 'HomeController@index');

Auth::routes();

/*
 * Shop
 */
Route::get('shop/search', ['uses' => 'ShopController@search', 'as' => 'shop.search']);
Route::get('shop/{product_category?}', ['uses' => 'ShopController@index', 'as' => 'products.index']);
Route::get('shop/{product_category}/{listed_product_slug}', ['uses' => 'ProductsController@show', 'as' => 'products.show']);

Route::get('cart', ['uses' => 'CartController@index', 'as' => 'cart', 'middleware' => 'cart.empty']);
Route::post('cart', ['uses' => 'CartController@store', 'as' => 'cart.store']);
Route::delete('cart/{rowid}', ['uses' => 'CartController@remove', 'as' => 'cart.remove']);

Route::get('checkout', ['uses' => 'CheckoutController@show', 'as' => 'checkout.show', 'middleware' => 'cart.empty']);
Route::get('checkout/shipping', ['uses' => 'CheckoutController@shipping', 'as' => 'checkout.shipping']);
Route::get('checkout/pay', ['uses' => 'CheckoutController@pay', 'as' => 'checkout.pay']);

Route::post('orders', ['uses' => 'OrdersController@store', 'as' => 'orders.store']);
Route::post('orders/shipping', ['uses' => 'OrdersController@shipping', 'as' => 'orders.shipping']);
Route::get('orders/{order}/pay', ['uses' => 'OrdersController@pay', 'as' => 'orders.pay']);

Route::post('payments', ['uses' => 'PaymentsController@store', 'as' => 'payments.store']);

Route::get('order-completed', ['uses' => 'OrdersController@completed', 'as' => 'orders.completed']);

/*
 * Contact
 */
Route::get('contact', ['uses' => 'ContactsController@create', 'as' => 'contacts.create']);
Route::post('contact', ['uses' => 'ContactsController@store', 'as' => 'contacts.store']);

/*
 * Account
 */
Route::group(['prefix' => 'account'], function () {
    Route::get('/', ['uses' => 'AccountsController@show', 'as' => 'accounts.show']);
    Route::get('edit', ['uses' => 'AccountsController@edit', 'as' => 'accounts.edit']);
    Route::patch('{user}', ['uses' => 'AccountsController@update', 'as' => 'accounts.update']);

    Route::get('orders/{order}', ['uses' => 'OrdersController@show', 'as' => 'orders.show']);

    Route::group(['prefix' => 'addresses'], function () {
        Route::get('/', ['uses' => 'AddressesController@index', 'as' => 'addresses.index']);
        Route::get('new', ['uses' => 'AddressesController@create', 'as' => 'addresses.create']);
        Route::get('{address}/edit', ['uses' => 'AddressesController@edit', 'as' => 'addresses.edit']);
        Route::post('/', ['uses' => 'AddressesController@store', 'as' => 'addresses.store']);
        Route::put('{address}', ['uses' => 'AddressesController@update', 'as' => 'addresses.update']);
        Route::delete('{address}', ['uses' => 'AddressesController@destroy', 'as' => 'addresses.delete']);
    });
});
