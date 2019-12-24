<?php

use App\Events\TaskEvent;
use App\Jobs\PendingNotification;
use App\ReferralGrant;


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
Route::get('/phpinfo',function(){
	return dd(phpinfo());
});

Route::group(['prefix' => 'test'], function() {
	Route::get('/notification/{id}','TestController@notification');
	Route::get('/mail','TestController@mail');
	Route::get('/random','TestController@random');
});

Route::get('/', 'HomeController@index')->name('dashboard');

Auth::routes();

Route::middleware(['auth'])->group(function () {
	Route::get('/authUser','CoreController@authUser');
	Route::get('/v/{any}', 'HomeController@index')->where('any', '.*');
	Route::group(['prefix' => 'admin', 'middleware' => ['role:superAdmin']], function() {
	    Route::resource('roles','RoleController');
	    Route::resource('users','UserController');
	});
	Route::get('firestore','FirestoreController@index');
	Route::get('set','FirestoreController@setData');
	Route::get('whereData','FirestoreController@whereData');

	Route::resource('/orders','OrderController');
	Route::get('/getOrders/{status}','OrderController@getOrders');
	Route::get('/getOrdersCount','OrderController@getOrdersCount');
	Route::get('/orders/count/indStatus','OrderController@getIndividualOrdersCount');
	Route::post('/assignOrder','OrderController@assignOrder');
	Route::post('/orders/search/{status}','OrderController@searchOrders');
	Route::post('/deleteMultipleOrders','OrderController@destroyMultipleOrders');
	
	Route::apiResource('/services','ServiceController');
	Route::apiResource('/categories','CategoryController');
	Route::apiResource('/items','ItemController');
	Route::get('/appDefaults','CoreController@appDefaults');
	Route::post('/appDefaults','CoreController@updateAppDefaults');
	Route::get('/mainAreas','CoreController@mainAreas');
	Route::post('/mainArea','CoreController@addMainArea');
	Route::delete('/mainArea/{id}','CoreController@deleteMainArea');
	
	Route::get('/offers','CoreController@offers');
	Route::post('/offers','CoreController@addOffer');
	Route::post('/offers/edit/{id}','CoreController@editOffer');
	Route::post('/changeOfferStatus','CoreController@changeOfferStatus');
	Route::delete('/offers/{id}','CoreController@deleteOffer');	

	Route::apiResource('/coupons','CouponController');

	Route::apiResource('/drivers','DriverController');
	Route::get('/driver/all','DriverController@allDrivers');
	Route::get('/driver/orders/{driver_id}','DriverController@driverOrders');

	Route::apiResource('/customers','CustomerController');
	Route::get('/unverifiedCustomers','CustomerController@unverifiedCustomers');
	Route::post('/deleteCustomers','CustomerController@deleteCustomers');
	Route::get('/address/{customer_id}','CustomerController@address');

	Route::get('/customer/all','CustomerController@all');

	Route::get('/notifications','UserController@notifications');
	Route::get('/markAsRead/{notificationId}','UserController@markAsRead');
	Route::get('/markAllAsRead','UserController@markAllAsRead');

	Route::get('event',function(){
		event(new TaskEvent('Hey how are you'));
	});

	Route::get('getFields/{fieldType}','CoreController@getFields');
	Route::get('getSettings/{settingType}','CoreController@getSettings');
	Route::get('orderTime','CoreController@orderTime');

	//REPORT GENERATION
	Route::group(['prefix' => 'reports'], function() {
	    Route::post('/totalOrders','ReportController@totalOrders');
	    Route::post('/totalCustomers','ReportController@totalCustomers');
	    Route::post('/totalSales','ReportController@totalSales');

	    //Exports
	    Route::get('/export','ReportController@export');
	});
	
	//PAYPAL INTEGRATION
	Route::get('/payment',function(){
		return view('test');
	});
	Route::post('/paypal/initiate','PaypalController@createPayment')->name('createPayment');
	Route::get('/paypal/execute/{order_id}','PaypalController@executePayment')->name('executePayment');
	Route::get('/paypal/retrieve/{paymentID}','PaypalController@retrievePayment');
});