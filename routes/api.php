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
		Route::post('/address/setDefault','CustomerController@setDefaultAddress');

		Route::get('/generateInvoice/{order_id}','OrderController@customerOrderInvoice');
		Route::get('/confirmInvoice/{order_id}','OrderController@customerConfirmInvoice');
		
		Route::delete('/cancelOrderForCustomer/{order_id}','OrderController@cancelOrderForCustomer');

		Route::apiResource('/cards','CardController');
	});
	
	Route::group(['middleware' => ['role:driver']], function() {
		Route::post('/acceptOrder','OrderController@acceptOrder');
		Route::post('/cancelPickup','OrderController@cancelPickup');
		Route::get('/pendingOrders','OrderController@pendingOrders');
		Route::get('/services','CoreController@services');
		Route::get('/items','CoreController@items');
		Route::get('/serviceWithItems','CoreController@serviceWithItems');

		Route::post('/driver/generateInvoice','OrderController@addItemsGenerateInvoice');
		Route::get('/driver/generateInvoice/{order_id}','OrderController@driverOrderInvoice');
		Route::get('/driver/orders','OrderController@orderListForDriver');

		Route::post('/sendOrderInvoiceForApproval','OrderController@sendOrderInvoiceForApproval');
		Route::get('/dropAtOffice/{order_id}','OrderController@driverDropAtOffice');
		Route::get('/pickedFromOffice/{order_id}','OrderController@driverPickedFromOffice');
		Route::post('/changeMainArea','DriverController@changeMainArea');
	});

	Route::get('/test','OrderController@test');
	//Driver API
	
	Route::get('/supportInfo','CoreController@supportInfo');
	Route::get('/orderDefaults','CoreController@orderDefaults');
	Route::get('/configs/{configType}','CoreController@getSettings');
	Route::get('/mainAreas','CoreController@mainAreas');
	Route::get('/offers','CoreController@offers');
	Route::get('/termsAndConditions','CoreController@termsAndConditions');
	Route::get('/servicesPlusItems','CoreController@servicesPlusItems');

	Route::get('/notifications','AuthController@notifications');
	Route::get('/countUnreadNotifications','AuthController@countUnreadNotifications');
	Route::get('/markAsRead/{notificationID}','AuthController@markAsRead');
	Route::get('/markAllAsRead','AuthController@markAllAsRead');

});