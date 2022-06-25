<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'v1'], function () {
    //Cart
    Route::post('login', 'App\Http\Controllers\api\v1\AuthController@login')->middleware(['throttle:5,1']);
    //Cart
    Route::post('cart', 'App\Http\Controllers\api\v1\CartController@save');
    Route::put('cart/{id}', 'App\Http\Controllers\api\v1\CartController@update');
    Route::delete('cart/{id}', 'App\Http\Controllers\api\v1\CartController@delete');
    Route::get('cart', 'App\Http\Controllers\api\v1\CartController@list');
    //Category
    Route::get('categories', 'App\Http\Controllers\api\v1\CategoryController@list');
    //Products
    Route::post('products', 'App\Http\Controllers\api\v1\ProductController@save');
    Route::get('products/{page?}', 'App\Http\Controllers\api\v1\ProductController@list');
    Route::post('products/{id}', 'App\Http\Controllers\api\v1\ProductController@details');
    Route::delete('products/{id}', 'App\Http\Controllers\api\v1\ProductController@delete');
});


