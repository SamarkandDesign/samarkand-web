<?php

Route::get('/', 'HomeController@index');

Route::get('/healthz', function () {
    return '200 OK';
});

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

Route::get('checkout', ['uses' => 'OrdersController@create', 'as' => 'checkout.show', 'middleware' => 'cart.empty']);
Route::get('checkout/shipping', ['uses' => 'ShippingMethodsController@show', 'as' => 'checkout.shipping']);
Route::get('checkout/pay', ['uses' => 'PaymentsController@create', 'as' => 'checkout.pay']);

Route::post('orders', ['uses' => 'OrdersController@store', 'as' => 'orders.store']);
Route::post('orders/shipping', ['uses' => 'ShippingMethodsController@store', 'as' => 'orders.shipping']);
Route::get('orders/{order}/pay', ['uses' => 'OrdersController@pay', 'as' => 'orders.pay']);

Route::post('payments', ['uses' => 'PaymentsController@store', 'as' => 'payments.store']);

Route::get('order-completed', ['uses' => 'OrdersController@completed', 'as' => 'orders.completed']);

/*
 * Contact
 */
Route::get('contact', ['uses' => 'ContactsController@create', 'as' => 'contacts.create']);
Route::post('contact', ['uses' => 'ContactsController@store', 'as' => 'contacts.store']);

Route::get('events', ['uses' => 'EventsController@index', 'as' => 'events.index']);
Route::get('event/{event_slug}', ['uses' => 'EventsController@show', 'as' => 'events.show']);

/*
 * Account
 */
Route::group(['prefix' => 'account'], function () {
    Route::get('/', ['uses' => 'AccountsController@show', 'as' => 'accounts.show']);
    Route::get('edit', ['uses' => 'AccountsController@edit', 'as' => 'accounts.edit']);
    Route::patch('{user}', ['uses' => 'AccountsController@update', 'as' => 'accounts.update']);

    Route::get('orders/{order}', ['uses' => 'OrdersController@show', 'as' => 'orders.show']);
    Route::get('orders/{order}/invoice', ['uses' => 'InvoicesController@show', 'as' => 'orders.invoices.show']);

    Route::group(['prefix' => 'addresses'], function () {
        Route::get('/', ['uses' => 'AddressesController@index', 'as' => 'addresses.index']);
        Route::get('new', ['uses' => 'AddressesController@create', 'as' => 'addresses.create']);
        Route::get('{address}/edit', ['uses' => 'AddressesController@edit', 'as' => 'addresses.edit']);
        Route::post('/', ['uses' => 'AddressesController@store', 'as' => 'addresses.store']);
        Route::patch('{address}', ['uses' => 'AddressesController@update', 'as' => 'addresses.update']);
        Route::delete('{address}', ['uses' => 'AddressesController@destroy', 'as' => 'addresses.delete']);
    });
});
