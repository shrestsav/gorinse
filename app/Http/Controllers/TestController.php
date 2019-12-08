<?php

namespace App\Http\Controllers;

use App\ReferralGrant;
use App\User;
use Illuminate\Http\Request;
use Mail;
use App\Mail\notifyMail;

class TestController extends Controller
{
    public function mail()
    {
    	$customerMailData = [
            'emailType' => 'new_order',
            'name'      => 'test',
            'email'     => 'shrestsav@gmail.com',
            'orderID'   => 'test',
            'subject'   => "test",
            'message'   => "test"
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        return 'yes';
    }
    public function notification($user_id)
    {
      $notification = [
          'notifyType' => 'test_notitification',
          'message' => 'This is a Test Notification, Thank you',
          'model' => 'order',
          'url' => 1
      ];
      // $user = User::find($user_id)->pushNotification($notification);
      try{
        User::find($user_id)->sendFCMNotification($notification);
      }
      catch(exception $e){
        return 'This User May not have any device tokens';
      }
      return 'Notification Sent';
    }

    public function random(Request $request)
    {
		// return number_format(1/3.6725,100)*1522.5;
		// $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->toDateTimeString();
		// return $today;
		// return Session::get('rows');
		// return $request->session()->all();
		// return 'Current PHP version: ' . phpversion();
		// return  PendingNotification::dispatch(31)->delay(now()->addSeconds(20));
		$test = new ReferralGrant();

		return $test->grantReferrer(62);
	}
}
