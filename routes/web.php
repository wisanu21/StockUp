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
Route::get('/register-company', 'web\RegisterCompanyController@index');
Route::post("/register-company-save","web\RegisterCompanyController@registerCompanySave");
Route::group(['middleware'=>'auth.check'], function(){
    Route::post('/login-restricted-area', 'web\LoginController@loginRestrictedArea');
    Route::get('/home', 'web\HomeController@index');
    Route::get('/logout', 'web\LoginController@logout');

    Route::group(['prefix' => 'manage-users'], function () { // จัดการผู้ใช้งานระบบ
        Route::get('/', 'web\ManageUsersController@list');
        Route::get("/edit/{id}","web\ManageUsersController@edit");
        Route::post("/edit-save","web\ManageUsersController@editSave");
        Route::get("/delete/{id}","web\ManageUsersController@delete");
        Route::post("/change-password","web\ManageUsersController@changePassword");
    });

    Route::group(['prefix' => 'manage-company'], function () { //จัดการร้านค้าทั้งหมด
        Route::get('/', 'web\ManageCompanyController@list');
        Route::get('/edit-company/{id}', 'web\ManageCompanyController@editCompany');
        Route::post("/edit-company-save","web\ManageCompanyController@editCompanySave");
        Route::get("/delete/{id}","web\ManageCompanyController@delete");
    });

    Route::group(['prefix' => 'employee'], function () { //จัดการพนักงาน
        Route::get('/', 'web\EmployeeController@list');
        Route::get("/edit/{id}","web\EmployeeController@edit");
        Route::post("/edit-save","web\EmployeeController@editSave");
        Route::get("/delete/{id}","web\EmployeeController@delete");
        Route::post("/change-password","web\EmployeeController@changePassword");
    });

    Route::group(['prefix' => 'product'], function () { //จัดการสินค้า
        Route::get('/', 'web\ProductController@list');
        Route::get("/add","web\ProductController@add");
        Route::post("/add-save","web\ProductController@addSave");
        Route::get("/detail/{id}","web\ProductController@detail");
        Route::get("/edit/{id}","web\ProductController@edit");
        Route::post("/edit-save","web\ProductController@editSave");
        Route::get("/delete/{id}","web\ProductController@delete");
    });
    
    Route::group(['prefix' => 'company'], function () { //จัดการร้านค้า
        Route::get('/', 'web\CompanyController@index');
        Route::post("/save","web\CompanyController@save");
        // Route::get("/add","web\ProductController@add");
        // Route::post("/add-save","web\ProductController@addSave");
        // Route::get("/detail/{id}","web\ProductController@detail");
        // Route::get("/edit/{id}","web\ProductController@edit");
        // Route::post("/edit-save","web\ProductController@editSave");
        // Route::get("/delete/{id}","web\ProductController@delete");
    });

    Route::group(['prefix' => 'promotion'], function () { //จัดการโปรโมชั่น
        Route::get('/', 'web\PromotionController@list');
        Route::get("/add","web\PromotionController@add");
        Route::post("/add-save","web\PromotionController@addSave");
        Route::get("/detail/{id}","web\PromotionController@detail");
        Route::get("/edit/{id}","web\PromotionController@edit");
        Route::post("/edit-save","web\PromotionController@editSave");
        Route::get("/delete/{id}","web\PromotionController@delete");
    });

    Route::group(['prefix' => 'order'], function () { // รับ Order
        Route::get('/', 'web\OrderController@list');
        Route::get('/create-order/{promotion_id}', 'web\OrderController@createOrder');
        Route::post("/submit-order","web\OrderController@submitOrder");
    });

    
    Route::group(['prefix' => 'manage-stock'], function () { // จัดการสต๊อก
        Route::get('/', 'web\StockController@list');
        Route::get("/add","web\StockController@add");
        Route::post("/add-save","web\StockController@addSave");
        Route::get("/detail/{id}","web\StockController@detail");
        Route::get("/edit/{id}","web\StockController@edit");
        Route::post("/edit-save","web\StockController@editSave");
        Route::get("/delete/{id}","web\StockController@delete");
        // Route::get('/create-order/{promotion_id}', 'web\OrderController@createOrder');
        // Route::post("/submit-order","web\OrderController@submitOrder");
    });

    Route::group(['prefix' => 'sales-summary'], function () { // จัดการสต๊อก
        Route::get('/', 'web\SalesSummaryController@index');
        Route::post('/index-post', 'web\SalesSummaryController@index');
        Route::get('/detail/{id}', 'web\SalesSummaryController@detail');
    });

});

