<?php

use App\Events\TaskEvent;
use App\Jobs\PendingNotification;
use App\ReferralGrant;


// use LaravelFCM\Message\OptionsBuilder;
// use LaravelFCM\Message\PayloadDataBuilder;
// use LaravelFCM\Message\PayloadNotificationBuilder;
// use FCM;


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
Route::get('/testNotification/{id}','OrderController@testNotification');

Route::get('/', 'HomeController@index')->name('dashboard');


Route::get('/beamTest', function(){

	$optionBuilder = new OptionsBuilder();
	$optionBuilder->setTimeToLive(60*20);

	$notificationBuilder = new PayloadNotificationBuilder('my title');
	$notificationBuilder->setBody('Hello world')
					    ->setSound('default');

	$dataBuilder = new PayloadDataBuilder();
	$dataBuilder->addData(['a_data' => 'my_data']);

	$option = $optionBuilder->build();
	$notification = $notificationBuilder->build();
	$data = $dataBuilder->build();

	// $token = "d-slsEd8Xrc:APA91bHBHsWLBcxjD_JXLXd3DS01IIlXf7RCIf…bxsnC-xwIG2hhCxc1PD9YAtTUvVN96aruRMYR4_gXaSQWVw3x";
	// $token = "eH-mZ5yUuAE:APA91bHJMtO2TOqhe8ATo9rFnVGGISf02ZkO73YJW_6CGX13SCwAOn0lTJO5cD11YYpnnrTZJ0lnr_IvIe6ALYgaHzqe8DfjdcIABgVgcWYsbxWGvQIiQaM4gKebt3oK3Cg6Zslg6vq-";
	$token = "dQkf55b3J1w:APA91bG7DJayDv1xJhGUSdjd-qs1xjejfZZAXfGrOi4XKSGeyKp-ebng6wXDR28_UL704yzBtZU14OlPniNOOu9NeYIiM_1c0Pm1HrXbFulhy0M_7zZ5XXzdw9jhZpr3Avb4g1_2R_eZ";

	$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

	$downstreamResponse->numberSuccess();
	$downstreamResponse->numberFailure();
	$downstreamResponse->numberModification();

	// return Array - you must remove all this tokens in your database
	$downstreamResponse->tokensToDelete();

	// return Array (key : oldToken, value : new token - you must change the token in your database)
	$downstreamResponse->tokensToModify();

	// return Array - you should try to resend the message to the tokens in the array
	$downstreamResponse->tokensToRetry();

	// return Array (key:token, value:error) - in production you should remove from your database the tokens
	$downstreamResponse->tokensWithError();



















	// $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
	//   "instanceId" => "36fe4e8b-da77-4025-9f02-3bc982e3e9b0",
	//   "secretKey" => "948E31F35E3DB6D9E30D59DB0E87777A15F39795F3C2E828015BF963D54BB675",
	// ));

	// $publishResponse = $beamsClient->publishToUsers(
	//   array("utsav", "shrestha"),
	//   array(
	//     "fcm" => array(
	//       "notification" => array(
	//         "title" => "Hi!",
	//         "body" => "This is my first Push Notification!"
	//       )
	//     ),
	//     "apns" => array("aps" => array(
	//       "alert" => array(
	//         "title" => "Hi!",
	//         "body" => "This is my first Push Notification!"
	//       )
	//     ))
	// ));

	// $userId = "user-001";
	// $token = $beamsClient->generateToken($userId);
	// // Return $token to device

	// return $token;
	return 'sent';
});
Auth::routes();
Route::get('/test',function(Request $request){
	// return number_format(1/3.6725,100)*1522.5;
	// $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->toDateTimeString();
	// return $today;
	// return Session::get('rows');
	// return $request->session()->all();
	// return 'Current PHP version: ' . phpversion();
	// return  PendingNotification::dispatch(31)->delay(now()->addSeconds(20));
	$test = new ReferralGrant();

	return $test->grantReferrer(62);
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

	Route::resource('/drivers','DriverController');

	Route::resource('/customers','CustomerController');
	Route::get('/unverifiedCustomers','CustomerController@unverifiedCustomers');
	Route::post('/deleteCustomers','CustomerController@deleteCustomers');
	Route::get('/address/{customer_id}','CustomerController@address');

	Route::get('/getCustomers','UserController@customers');

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
	    Route::get('/testReport','ReportController@test');
	});
	
	//PAYPAL INTEGRATION
	Route::get('/payment',function(){
		return view('test');
	});
	Route::post('/paypal/initiate','PaypalController@createPayment')->name('createPayment');
	Route::get('/paypal/execute/{order_id}','PaypalController@executePayment')->name('executePayment');
	Route::get('/paypal/retrieve/{paymentID}','PaypalController@retrievePayment');
});