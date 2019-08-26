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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => ['auth:api']], function() {
	Route::get('/checkRole','AuthController@checkRole');
    Route::apiResource('/orders','OrderController');

	Route::group(['middleware' => ['role:customer']], function() {
		Route::post('/createProfile','AuthController@createProfile');

		Route::apiResource('/customers','CustomerController');
		Route::post('/updateProfile','CustomerController@updateProfile');
		Route::post('/changePhone','CustomerController@changePhone');
		Route::post('/updatePhone','CustomerController@updatePhone');

		Route::get('/getAddress','CustomerController@getAddress');
		Route::post('/addAddress','CustomerController@addAddress');
		Route::post('/updateAddress','CustomerController@updateAddress');
		Route::get('/generateInvoice/{order_id}','OrderController@customerOrderInvoice');
		Route::get('/confirmInvoice/{order_id}','OrderController@customerConfirmInvoice');

		Route::apiResource('/cards','CardController');
	});
	
	Route::group(['middleware' => ['role:driver']], function() {
		Route::post('/acceptOrder','OrderController@acceptOrder');
		Route::get('/pendingOrders','OrderController@pendingOrders');
		Route::get('/services','CoreController@services');
		Route::get('/items','CoreController@items');
		Route::post('/orderItems','OrderController@orderItems');
		Route::post('/sendOrderInvoiceForApproval','OrderController@sendOrderInvoiceForApproval');
		Route::get('/dropAtOffice/{order_id}','OrderController@driverDropAtOffice');
	});

	Route::get('/test','OrderController@test');
	//Driver API
	
	Route::get('/supportInfo','CoreController@supportInfo');
	Route::get('/orderDefaults','CoreController@orderDefaults');
	Route::get('/configs/{configType}','CoreController@getSettings');
	Route::get('/mainAreas','CoreController@mainAreas');
	Route::get('/offers','CoreController@offers');
});