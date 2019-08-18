<?php

use Illuminate\Http\Request;

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
Route::post('/phoneRegister','Api\AuthController@phoneRegister');
Route::post('/verifyOTP','Api\AuthController@verifyOTP');

Route::get('/test','Api\AuthController@test');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => ['auth:api']], function() {
	Route::get('/checkRole','AuthController@checkRole');
    Route::apiResource('/orders','OrderController');

    Route::post('/createProfile','AuthController@createProfile');

	Route::apiResource('/customers','CustomerController');
	Route::post('/updateProfile','CustomerController@updateProfile');
	Route::post('/changePhone','CustomerController@changePhone');
	Route::post('/updatePhone','CustomerController@updatePhone');

    Route::get('/getAddress','CustomerController@getAddress');
	Route::post('/addAddress','CustomerController@addAddress');
	Route::post('/updateAddress','CustomerController@updateAddress');
	
	Route::post('/orderItems','OrderController@orderItems');
	Route::get('/test','OrderController@test');
	
	Route::group(['middleware' => ['role:driver']], function() {
		Route::post('/acceptOrder','OrderController@acceptOrder');
		Route::get('/pendingOrders','OrderController@pendingOrders');
		Route::get('/services','CoreController@services');
		Route::get('/items','CoreController@items');
	});

	//Driver API
	

	Route::get('/configs/{configType}','CoreController@getSettings');
});