<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

Route::get('products', 'ProductController@getProducts');
Route::post('products', 'ProductController@postProducts');

Route::post('products/upload', 'ProductController@uploadImage');

Route::get('products/{id}', 'ProductController@getProduct');
Route::put('products/{id}', 'ProductController@putProducts');

Route::get('categories', 'CategoryController@getCategories');
Route::post('categories', 'CategoryController@postCategory');



