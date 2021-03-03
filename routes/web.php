<?php

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
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'web\LoginController@index');
Route::post('/login', 'web\LoginController@login');

Route::get('/register', 'web\RegisterController@index');
Route::post('/register', 'web\RegisterController@register');

Route::group(['middleware'=>'auth.check'], function(){
    Route::get('/home', 'web\HomeController@index');

    Route::get('/manage-users', 'web\HomeController@index');
    Route::get('/logout', 'web\LoginController@logout');


    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'web\ProductController@list');
        Route::get("/add","web\ProductController@add");
        Route::post("/add-save","web\ProductController@addSave");
        Route::get("/detail/{id}","web\ProductController@detail");
        Route::get("/edit/{id}","web\ProductController@edit");
        Route::post("/edit-save","web\ProductController@editSave");
        Route::get("/delete/{id}","web\ProductController@delete");
    });

    Route::group(['prefix' => 'promotion'], function () {
        Route::get('/', 'web\PromotionController@list');
        Route::get("/add","web\PromotionController@add");
        Route::post("/add-save","web\PromotionController@addSave");
        Route::get("/detail/{id}","web\PromotionController@detail");
        Route::get("/edit/{id}","web\PromotionController@edit");
        Route::post("/edit-save","web\PromotionController@editSave");
        Route::get("/delete/{id}","web\PromotionController@delete");
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('/', 'web\OrderController@list');
        Route::get('/create-order/{promotion_id}', 'web\OrderController@createOrder');
    });
});