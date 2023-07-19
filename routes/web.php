<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// add product
// Route::resource('/products', 'ProductController');

Route::group(['middleware' => ['auth', 'cekName:Admin']], function () {
    // add product admin
    Route::get('products/create', 'ProductController@create');
    Route::post('products', 'ProductController@store');
    Route::delete('products/{id}', 'ProductController@destroy');
});

Route::group(['middleware' => ['auth']], function () {
    // add product
    Route::get('products', 'ProductController@index');
    Route::get('products/{id}/edit', 'ProductController@edit');
    Route::put('products/{id}', 'ProductController@update');

    // show user add product
    Route::get('/show', 'ProductController@show_user');
    
    // add image product
    Route::get('products/{productID}/images','ProductController@images');
    Route::get('products/{productID}/add-image','ProductController@add_image');
    Route::post('products/images/{productID}','ProductController@upload_image');
    Route::delete('products/images/{imageID}','ProductController@remove_image');
    
    // add attributes
    Route::resource('/attributes', 'AttributeController');
    // add options attribute
    Route::get('attributes/{attributeID}/add-option', 'AttributeController@add_option');
    Route::post('attributes/options/{attributeID}', 'AttributeController@store_option');
    Route::get('attributes/options/{optionID}/edit', 'AttributeController@edit_option');
    Route::put('attributes/options/{optionID}', 'AttributeController@update_option');
    Route::delete('attributes/options/{optionID}', 'AttributeController@remove_option');
    
    // add categories
    Route::resource('/categories', 'CategoryController');
});
