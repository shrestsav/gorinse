<?php

use App\Events\TaskEvent;
use App\Jobs\PendingNotification;

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

Route::get('/', 'HomeController@index')->name('dashboard');

Auth::routes();
Route::get('/test',function(){
	// PendingNotification::dispatch();
	 PendingNotification::dispatch(2)
                ->delay(now()->addSeconds(5));
	return 'yes';
});
Route::middleware(['auth'])->group(function () {
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
	Route::post('/assignOrder','OrderController@assignOrder');
	
	Route::get('/services','CoreController@services');
	Route::post('/services','CoreController@addService');	
	Route::get('/categories','CoreController@categories');
	Route::post('/categories','CoreController@addCategory');
	Route::get('/items','CoreController@items');
	Route::post('/items','CoreController@addItem');
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

	Route::resource('/drivers','DriverController');

	Route::resource('/customers','CustomerController');

	Route::get('/getCustomers','UserController@customers');

	Route::get('/notifications','UserController@notifications');
	Route::get('/markAllAsRead','UserController@markAllAsRead');
	Route::get('/testNotification/{user_id}','OrderController@testNotification');

	Route::get('event',function(){
		event(new TaskEvent('Hey how are you'));
	});
	Route::get('getFields/{fieldType}','CoreController@getFields');
	Route::get('getSettings/{settingType}','CoreController@getSettings');
	Route::get('orderTime','CoreController@orderTime');
});