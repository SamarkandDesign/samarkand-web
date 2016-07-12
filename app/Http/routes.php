<?php

 // DB::listen(function ($query) {
 //    var_dump($query->sql, $query->bindings, $query->time);
 //        });

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'HomeController@index');

    Route::controllers([
        'password' => 'Auth\PasswordController',
    ]);

    /*
     * Registration
     */
    Route::get('register', ['uses' => 'Auth\AuthController@getRegister', 'as' => 'auth.register']);
    Route::post('register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'auth.register']);

    /*
     * Authentication
     */
    Route::get('login', ['uses' => 'Auth\AuthController@getLogin', 'as' => 'auth.login']);
    Route::post('login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'auth.login']);
    Route::get('logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'auth.logout']);

    /*
     * Shop
     */
    Route::get('shop/search', ['uses' => 'ShopController@search', 'as' => 'shop.search']);
    Route::get('shop/{product_category?}', ['uses' => 'ShopController@index', 'as' => 'products.index']);
    Route::get('shop/{product_category}/{product_slug}', ['uses' => 'ProductsController@show', 'as' => 'products.show']);

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
}); // /web middleware group

/*
 * Admin Area
 */
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin'], 'namespace' => 'Admin'], function () {
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

        Route::post('/', ['uses' => 'ProductsController@store', 'as' => 'admin.products.store']);
        Route::patch('/{product}', ['uses' => 'ProductsController@update', 'as' => 'admin.products.update']);
        Route::put('/{trashedProduct}/restore', ['uses' => 'ProductsController@restore', 'as' => 'admin.products.restore']);


        Route::delete('/{trashedProduct}', ['uses' => 'ProductsController@destroy', 'as' => 'admin.products.delete']);
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

        Route::get('{username}/orders', ['uses' => 'UsersController@orders', 'as' => 'admin.users.orders']);
        Route::get('{username}/addresses', ['uses' => 'UsersController@addresses', 'as' => 'admin.users.addresses']);
    });
});

/*
 * Api
 */
Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'Api'], function () {
    Route::get('terms/{taxonomy}', ['uses' => 'TermsController@terms', 'as' => 'api.terms']);

    // Attributes
    Route::get('product_attributes/{slug?}', ['uses' => 'ProductAttributesController@index', 'as' => 'api.product_attributes.index']);
    Route::post('product_attributes/{product_attribute}/attribute_properties', ['uses' => 'AttributePropertiesController@store', 'as' => 'api.product_attributes.attribute_properties.store']);

    Route::get('attribute_properties/{product_attribute?}', ['uses' => 'AttributePropertiesController@index', 'as' => 'api.attribute_properties.index']);
    Route::patch('attribute_properties/{attribute_property}', ['uses' => 'AttributePropertiesController@update', 'as' => 'api.attribute_properties.update']);
    Route::delete('attribute_properties/{attribute_property}', ['uses' => 'AttributePropertiesController@destroy', 'as' => 'api.attribute_properties.delete']);

    Route::get('categories', ['uses' => 'TermsController@categories', 'as' => 'api.categories']);
    Route::post('categories', ['uses' => 'TermsController@storeCategory', 'as' => 'api.categories']);

    Route::get('posts/{post}/images', ['uses' => 'MediaController@modelImages', 'as' => 'api.posts.images']);
    Route::get('products/{product}/images', ['uses' => 'MediaController@modelImages', 'as' => 'api.products.images']);

    Route::get('pages/{page}/images', ['uses' => 'MediaController@modelImages', 'as' => 'api.pages.images']);

    Route::get('media/{media}', ['uses' => 'MediaController@show', 'as' => 'api.media.show'])->where('id', '[0-9]+');
    Route::get('media/{collection?}', ['uses' => 'MediaController@index', 'as' => 'api.media.index']);


    // Protected API routes
    Route::group(['middleware' => 'admin'], function () {
        // Attach media to models
        Route::post('pages/{page}/image', ['uses' => 'MediaController@store', 'as' => 'api.pages.media.store']);
        Route::post('products/{product}/image', ['uses' => 'MediaController@store', 'as' => 'api.products.media.store']);
        Route::post('posts/{post}/image', ['uses' => 'MediaController@store', 'as' => 'api.posts.media.store']);

        // Terms
        Route::post('terms', ['uses' => 'TermsController@store', 'as' => 'api.terms.store']);
        Route::delete('terms/{term}', ['uses' => 'TermsController@destroy', 'as' => 'api.terms.delete']);
        Route::patch('terms/{term}', ['uses' => 'TermsController@update', 'as' => 'api.terms.update']);

        // Update an order
        Route::patch('orders/{order}', ['uses' => 'OrdersController@update', 'as' => 'api.orders.update']);

        // Media
        Route::patch('media/{media}', ['uses' => 'MediaController@update', 'as' => 'api.images.update']);
        Route::delete('media/{media}', ['uses' => 'MediaController@destroy', 'as' => 'api.images.destroy']);

        // Product Attributes
        Route::post('product_attributes', ['uses' => 'ProductAttributesController@store', 'as' => 'api.product_attributes.store']);
        Route::delete('product_attributes/{product_attribute}', ['uses' => 'ProductAttributesController@destroy', 'as' => 'api.product_attributes.delete']);
        Route::patch('product_attributes/{product_attribute}', ['uses' => 'ProductAttributesController@update', 'as' => 'api.product_attributes.update']);
    });
});

/*
 * If none of the above routes are matched we will see if a page has a matching path
 */
Route::get('{path}', ['uses' => 'PagesController@show', 'middleware' => ['web']])->where('path', '.+');
