<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/get-company', 'API\CompanyController@getAllCompanys');
Route::get('/get-company-by-is-register', 'API\CompanyController@getCompanysByIsRegister');
Route::post('/get-company-by-employee-id', 'API\CompanyController@getCompanyByEmployeeId');

Route::post('/save-register', 'API\RegisterController@saveRegister');
Route::post('/login', 'API\LoginController@login');
Route::post('/login-by-device-id', 'API\LoginController@loginByDeviceID');
Route::post('/validation-passport', 'API\ValidationPassportController@ValidationPassport');
Route::post('/log-out', 'API\LoginController@Logout');

Route::post('/get-data-dashboard', 'API\Pages\DashboardController@getDataDashboard');

Route::get('/get-image', 'API\Pages\DashboardController@image');

// Route::get('/test-index', 'API\testController@index');
// Route::get('/welcome', function () {
//     // return $request->all();
//     return view('welcome');
// });
// });
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
