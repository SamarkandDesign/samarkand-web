<?php

Route::get('/', ['uses' => 'AdminController@dashboard', 'as' => 'admin.dashboard']);

    // Orders
    Route::get('orders/{order}', ['uses' => 'OrdersController@show', 'as' => 'admin.orders.show'])->where('order', '[0-9]+');
    Route::get('orders/{order_status?}', ['uses' => 'OrdersController@index', 'as' => 'admin.orders.index']);
    Route::patch('orders/{order}', ['uses' => 'OrdersController@update', 'as' => 'admin.orders.update']);

    // Shipping Methods
    Route::group(['prefix' => 'shipping-methods'], function () {
        Route::get('/', ['uses' => 'ShippingMethodsController@index', 'as' => 'admin.shipping_methods.index']);
        Route::post('/', ['uses' => 'ShippingMethodsController@store', 'as' => 'admin.shipping_methods.store']);
        Route::get('/{shipping_method}/edit', ['uses' => 'ShippingMethodsController@edit', 'as' => 'admin.shipping_methods.edit']);
        Route::patch('/{shipping_method}', ['uses' => 'ShippingMethodsController@update', 'as' => 'admin.shipping_methods.update']);
        Route::delete('/{shipping_method}', ['uses' => 'ShippingMethodsController@destroy', 'as' => 'admin.shipping_methods.delete']);
    });

    // Posts
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', ['uses' => 'PostsController@index', 'as' => 'admin.posts.index']);
        Route::get('/create', ['uses' => 'PostsController@create', 'as' => 'admin.posts.create']);
        Route::get('/trash', ['uses' => 'PostsController@trash', 'as' => 'admin.posts.trash']);
        Route::post('/', ['uses' => 'PostsController@store', 'as' => 'admin.posts.store']);

        Route::put('/{trashedPost}/restore', ['uses' => 'PostsController@restore', 'as' => 'admin.posts.restore']);
        Route::get('/{post}/edit', ['uses' => 'PostsController@edit', 'as' => 'admin.posts.edit']);

        Route::get('/{post}/images', ['uses' => 'PostsController@images', 'as' => 'admin.posts.images']);

        Route::patch('/{post}', ['uses' => 'PostsController@update', 'as' => 'admin.posts.update']);
        Route::delete('/{trashedPost}', ['uses' => 'PostsController@destroy', 'as' => 'admin.posts.delete']);
    });

    // Pages
    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', ['uses' => 'PagesController@index', 'as' => 'admin.pages.index']);
        Route::get('/create', ['uses' => 'PagesController@create', 'as' => 'admin.pages.create']);
        Route::get('/{page}/edit', ['uses' => 'PagesController@edit', 'as' => 'admin.pages.edit']);
        Route::get('/trash', ['uses' => 'PagesController@trash', 'as' => 'admin.pages.trash']);

        Route::post('', ['uses' => 'PagesController@store', 'as' => 'admin.pages.store']);
        Route::put('/{trashedPage}/restore', ['uses' => 'PagesController@restore', 'as' => 'admin.pages.restore']);
        Route::patch('/{page}', ['uses' => 'PagesController@update', 'as' => 'admin.pages.update']);
        Route::delete('/{trashedPage}', ['uses' => 'PagesController@destroy', 'as' => 'admin.pages.delete']);
    });

    // Products
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', ['uses' => 'ProductsController@index', 'as' => 'admin.products.index']);
        Route::get('/create', ['uses' => 'ProductsController@create', 'as' => 'admin.products.create']);
        Route::get('/{product}/edit', ['uses' => 'ProductsController@edit', 'as' => 'admin.products.edit']);
        Route::get('/trash', ['uses' => 'ProductsController@trash', 'as' => 'admin.products.trash']);
        Route::get('/search', ['uses' => 'ProductsController@search', 'as' => 'admin.products.search']);

        Route::post('/', ['uses' => 'ProductsController@store', 'as' => 'admin.products.store']);
        Route::patch('/{product}', ['uses' => 'ProductsController@update', 'as' => 'admin.products.update']);
        Route::put('/{trashedProduct}/restore', ['uses' => 'ProductsController@restore', 'as' => 'admin.products.restore']);


        Route::delete('/{trashedProduct}', ['uses' => 'ProductsController@destroy', 'as' => 'admin.products.delete']);
    });

    // Events
    Route::group(['prefix' => 'events'], function () {
        Route::get('/', ['uses' => 'EventsController@index', 'as' => 'admin.events.index']);
        Route::get('/create', ['uses' => 'EventsController@create', 'as' => 'admin.events.create']);
        Route::post('/', ['uses' => 'EventsController@store', 'as' => 'admin.events.store']);
        Route::get('/{event}/edit', ['uses' => 'EventsController@edit', 'as' => 'admin.events.edit']);
        Route::patch('/{event}', ['uses' => 'EventsController@update', 'as' => 'admin.events.update']);
        Route::delete('/{event}', ['uses' => 'EventsController@destroy', 'as' => 'admin.events.delete']);
    });

    // Addresses
    Route::group(['prefix' => 'addresses'], function () {
        Route::get('/', ['uses' => 'AddressesController@index', 'as' => 'admin.addresses.index']);
        Route::get('{address}/edit', ['uses' => 'AddressesController@edit', 'as' => 'admin.addresses.edit']);
    });

    // Terms
    Route::get('terms/{taxonomy}', ['uses' => 'TermsController@index', 'as' => 'admin.terms.index']);
    Route::get('terms/{term}/edit', ['uses' => 'TermsController@edit', 'as' => 'admin.terms.edit']);
    Route::patch('terms/{term}', ['uses' => 'TermsController@update', 'as' => 'admin.terms.update']);
    Route::delete('terms/{term}', ['uses' => 'TermsController@destroy', 'as' => 'admin.terms.delete']);

    Route::get('tags', ['uses' => 'TermsController@tagsIndex', 'as' => 'admin.tags.index']);

    Route::get('categories', ['uses' => 'TermsController@categoriesIndex', 'as' => 'admin.categories.index']);
    Route::get('categories/{term}/edit', ['uses' => 'TermsController@edit', 'as' => 'admin.categories.edit']);
    Route::patch('categories/{term}', ['uses' => 'TermsController@update', 'as' => 'admin.categories.update']);

    // Product Attributes
    Route::group(['prefix' => 'attributes'], function () {
        Route::get('/', ['uses' => 'AttributesController@index', 'as' => 'admin.attributes.index']);
        Route::get('/create', ['uses' => 'AttributesController@create', 'as' => 'admin.attributes.create']);
        Route::post('/', ['uses' => 'AttributesController@store', 'as' => 'admin.attributes.store']);
        Route::patch('{product_attribute}', ['uses' => 'AttributesController@update', 'as' => 'admin.attributes.update']);
        Route::get('/{product_attribute}/edit', ['uses' => 'AttributesController@edit', 'as' => 'admin.attributes.edit']);
        Route::delete('/{product_attribute}', ['uses' => 'AttributesController@destroy', 'as' => 'admin.attributes.delete']);
    });

    // Media
    Route::get('media', ['uses' => 'MediaController@index', 'as' => 'admin.media.index']);
    Route::delete('media/{media}', ['uses' => 'MediaController@destroy', 'as' => 'admin.media.delete']);

    // Users
    Route::get('profile', ['uses' => 'UsersController@profile', 'as' => 'admin.users.profile']);

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', ['uses' => 'UsersController@index', 'as' => 'admin.users.index']);
        Route::get('new', ['uses' => 'UsersController@create', 'as' => 'admin.users.create']);
        Route::post('/', ['uses' => 'UsersController@store', 'as' => 'admin.users.store']);
        Route::get('{user}', ['uses' => 'UsersController@edit', 'as' => 'admin.users.edit'])->where('user', '[0-9]+');
        Route::get('{username}', ['uses' => 'UsersController@edit', 'as' => 'admin.users.edit']);

        Route::patch('{user}', ['as' => 'admin.users.update', 'uses' => 'UsersController@update']);
        Route::patch('{user}/token', ['uses' => 'UsersController@token', 'as' => 'admin.users.token']);

        Route::get('{username}/orders', ['uses' => 'UsersController@orders', 'as' => 'admin.users.orders']);
        Route::get('{username}/addresses', ['uses' => 'UsersController@addresses', 'as' => 'admin.users.addresses']);
    });
