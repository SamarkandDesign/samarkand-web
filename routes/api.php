<?php

Route::get('terms/{taxonomy}', ['uses' => 'TermsController@terms', 'as' => 'api.terms']);

Route::post('feedbacks', ['uses' => 'FeedbacksController@store']);

// Attributes
Route::get('product_attributes/{slug?}', [
  'uses' => 'ProductAttributesController@index',
  'as' => 'api.product_attributes.index',
]);
Route::post('product_attributes/{product_attribute}/attribute_properties', [
  'uses' => 'AttributePropertiesController@store',
  'as' => 'api.product_attributes.attribute_properties.store',
]);

Route::get('attribute_properties/{product_attribute?}', [
  'uses' => 'AttributePropertiesController@index',
  'as' => 'api.attribute_properties.index',
]);
Route::patch('attribute_properties/{attribute_property}', [
  'uses' => 'AttributePropertiesController@update',
  'as' => 'api.attribute_properties.update',
]);
Route::delete('attribute_properties/{attribute_property}', [
  'uses' => 'AttributePropertiesController@destroy',
  'as' => 'api.attribute_properties.delete',
]);

Route::get('categories', ['uses' => 'TermsController@categories', 'as' => 'api.categories']);
Route::post('categories', ['uses' => 'TermsController@storeCategory', 'as' => 'api.categories']);

Route::get('posts/{post}/images', [
  'uses' => 'MediaController@modelImages',
  'as' => 'api.posts.images',
]);

Route::get('products/{product}/images', [
  'uses' => 'MediaController@modelImages',
  'as' => 'api.products.images',
]);
Route::get('products/feed.txt', [
  'uses' => 'ProductsController@feed',
  'as' => 'api.products.feed.txt',
]);

Route::get('events/{event}/images', [
  'uses' => 'MediaController@modelImages',
  'as' => 'api.events.images',
]);

Route::get('pages/{page}/images', [
  'uses' => 'MediaController@modelImages',
  'as' => 'api.pages.images',
]);

Route::get('media/{media}', ['uses' => 'MediaController@show', 'as' => 'api.media.show'])->where(
  'id',
  '[0-9]+'
);
Route::get('media/{collection?}', ['uses' => 'MediaController@index', 'as' => 'api.media.index']);

// Protected API routes
Route::group(['middleware' => 'can:access-admin'], function () {
  // Attach media to models
  Route::post('pages/{page}/image', [
    'uses' => 'MediaController@store',
    'as' => 'api.pages.media.store',
  ]);
  Route::post('products/{product}/image', [
    'uses' => 'MediaController@store',
    'as' => 'api.products.media.store',
  ]);
  Route::post('posts/{post}/image', [
    'uses' => 'MediaController@store',
    'as' => 'api.posts.media.store',
  ]);
  Route::post('events/{event}/image', [
    'uses' => 'MediaController@store',
    'as' => 'api.events.media.store',
  ]);

  // Terms
  Route::post('terms', ['uses' => 'TermsController@store', 'as' => 'api.terms.store']);
  Route::delete('terms/{term}', ['uses' => 'TermsController@destroy', 'as' => 'api.terms.delete']);
  Route::patch('terms/{term}', ['uses' => 'TermsController@update', 'as' => 'api.terms.update']);

  // Update an order
  Route::patch('orders/{order}', [
    'uses' => 'OrdersController@update',
    'as' => 'api.orders.update',
  ]);

  // Media
  Route::patch('media/{media}', ['uses' => 'MediaController@update', 'as' => 'api.images.update']);
  Route::delete('media/{media}', [
    'uses' => 'MediaController@destroy',
    'as' => 'api.images.destroy',
  ]);

  // Product Attributes
  Route::post('product_attributes', [
    'uses' => 'ProductAttributesController@store',
    'as' => 'api.product_attributes.store',
  ]);
  Route::delete('product_attributes/{product_attribute}', [
    'uses' => 'ProductAttributesController@destroy',
    'as' => 'api.product_attributes.delete',
  ]);
  Route::patch('product_attributes/{product_attribute}', [
    'uses' => 'ProductAttributesController@update',
    'as' => 'api.product_attributes.update',
  ]);

  Route::get('data/sales', ['uses' => 'DataController@sales']);
});
