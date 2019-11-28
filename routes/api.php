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
    Route::post('/orders/checkCoupon','OrderController@checkCoupon');

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
		Route::delete('/deleteAddress/{id}','CustomerController@deleteAddress');

		Route::get('/order/active','OrderController@activeOrderListCustomer');
		Route::get('/order/history','OrderController@deliveredOrderListCustomer');
		Route::get('/generateInvoice/{order_id}','OrderController@customerOrderInvoice');
		Route::get('/confirmInvoice/{order_id}','OrderController@customerConfirmInvoice');
		
		Route::delete('/cancelOrderForCustomer/{order_id}','OrderController@cancelOrderForCustomer');

		Route::apiResource('/cards','CardController');
	});
	
	Route::group(['middleware' => ['role:driver']], function() {
		Route::get('/driver/details','DriverController@index');

		Route::post('/acceptOrder','DriverOrderController@acceptOrder');
		Route::post('/cancelPickup','DriverOrderController@cancelPickup');
		
		//This API has been divided into three seperate routes below
		Route::get('/pendingOrders','DriverOrderController@pendingOrders');
		
		Route::get('/driver/order/active','DriverOrderController@active');
		Route::get('/driver/order/pending','DriverOrderController@pending');
		Route::get('/driver/order/drop','DriverOrderController@drop');

		Route::get('/services','CoreController@services');
		Route::get('/items','CoreController@items');
		Route::get('/serviceWithItems','CoreController@serviceWithItems');

		Route::post('/driver/generateInvoice','DriverOrderController@addItemsGenerateInvoice');
		
		Route::get('/driver/generateInvoice/{order_id}','DriverOrderController@driverOrderInvoice');
		Route::get('/driver/orders','DriverOrderController@orderListForDriver');

		Route::post('/sendOrderInvoiceForApproval','DriverOrderController@sendOrderInvoiceForApproval');
		Route::get('/dropAtOffice/{order_id}','DriverOrderController@driverDropAtOffice');
		Route::get('/pickedFromOffice/{order_id}','DriverOrderController@driverPickedFromOffice');
		Route::get('/deliveredToCustomer/{order_id}','DriverOrderController@deliveredToCustomer');
		
		Route::post('/changeMainArea','DriverController@changeMainArea');
	});

	Route::get('/test','OrderController@test');
	Route::get('/tokens','AuthController@tokens');
	
	Route::get('/supportInfo','CoreController@supportInfo');
	Route::get('/orderDefaults','CoreController@orderDefaults');
	Route::get('/configs/{configType}','CoreController@getSettings');
	Route::get('/mainAreas','CoreController@mainAreas');
	Route::get('/offers','CoreController@offers');
	Route::get('/termsAndConditions','CoreController@termsAndConditions');
	Route::get('/FAQS','CoreController@FAQS');
	Route::get('/orderTypeDesc','CoreController@orderTypeDesc');
	Route::get('/servicesPlusItems','CoreController@servicesPlusItems');

	Route::get('/notifications','AuthController@notifications');
	Route::get('/countUnreadNotifications','AuthController@countUnreadNotifications');
	Route::get('/markAsRead/{notificationID}','AuthController@markAsRead');
	Route::get('/markAllAsRead','AuthController@markAllAsRead');

});