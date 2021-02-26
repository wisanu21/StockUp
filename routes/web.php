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
});