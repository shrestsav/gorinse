<?php

namespace App;

use App\Notifications\OTPNotification;
use App\Notifications\SystemNotification;
use App\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname','lname', 'email', 'password','phone','OTP','OTP_timestamp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->phone;
    }
    
    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('phone', $username)->first();
    }

    /**
    * Validate the password of the user for the Passport password grant.
    *
    * @param  string $password
    * @return bool
    */
    public function validateForPassportPasswordGrant($OTP)
    {
        // return Hash::check($password, $this->password);
        $OTP_timestamp = \Carbon\Carbon::parse($this->OTP_timestamp);
        $current = \Carbon\Carbon::now();
        $totalTime = \Carbon\Carbon::now()->diffInMinutes($OTP_timestamp);

        if($this->OTP==$OTP && $totalTime<=config('settings.OTP_expiry'))
            return true;

        return false;
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'customer_id');
    }

    public function driverList()
    {
        $drivers = $this->whereHas('roles', function ($query) {
                          $query->where('name', '=', 'driver');
                       });

        return $drivers->with('details')->get();
    }

    public function adminList()
    {
        $superAdmin = $this->whereHas('roles', function ($query) {
                          $query->where('name', '=', 'superAdmin');
                       });

        return $superAdmin->get();
    }

    public function customerList()
    {
        $customers = $this->whereHas('roles', function ($query) {
                          $query->where('name', '=', 'customer');
                       });

        return $customers->get();
    }

    public function sendOTP()
    {
        $OTP = $this->OTP;
        $this->notify(new OTPNotification($OTP));
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /*****************************
    *
    * Notifications
    *
    ******************************/
    public function pushNotification($notification)
    {   
        $this->notify(new SystemNotification($notification));
    }
    
    public static function notifyNewOrder($order_id)
    {  
        //All Admins and Customer who ordered will get notified

        $order = Order::find($order_id);
        $area_id = $order->pick_location_details->area_id;
        $driver_ids = User::whereHas('roles', function ($query) {
                              $query->where('name', '=', 'driver');
                           })
                            ->join('user_details as ud','users.id','=','ud.user_id')
                            ->where('ud.area_id','=',$area_id)
                            ->pluck('users.id')
                            ->toArray();
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                          $query->where('name', '=', 'superAdmin');
                       })->pluck('id')->toArray();

        // $customer_id = $order->customer_id;

        $notification = [
            'notifyType' => 'new_order',
            'message' => $order->customer->fname. ' just created a new order '.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }

        // Send Notification to All Drivers of that particular area
        foreach($driver_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        
        return true;
    }

    public static function notifyAcceptOrder($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        // return $order->driver_id;
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                          $query->where('name', '=', 'superAdmin');
                       })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notification = [
            'notifyType' => 'order_accepted',
            'message' => $order->driver->fname. ' just accepted order '.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        // Send Order Accepted Notification to Customer
            User::find($customer_id)->pushNotification($notification);
        
        return true;
    }

    public static function notifyInvoiceGenerated($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                  $query->where('name', '=', 'superAdmin');
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'invoice_generated',
            'message' => $order->driver->fname. ' has generated an invoice for order '.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
            'notifyType' => 'invoice_generated',
            'message' => 'An Invoice has been generated for your order, please check and confirm your order',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->pushNotification($notificationCustomer);
        
        return true;
    }

    public static function notifyInvoiceConfirmed($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                  $query->where('name', '=', 'superAdmin');
                               })->pluck('id')->toArray();

        $driver_id = $order->driver_id;

        $notification = [
            'notifyType' => 'invoice_confirmed',
            'message' => $order->customer->fname. ' has confirmed invoice for order '.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        // Send Order Accepted Notification to Customer
        User::find($driver_id)->pushNotification($notification);
        
        return true;
    }

    public static function notifyDroppedAtOffice($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                  $query->where('name', '=', 'superAdmin');
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'dropped_at_office',
            'message' => $order->driver->fname. ' has dropped clothes at office for order '.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
            'notifyType' => 'dropped_at_office',
            'message' => 'Your clothes for order has been sent for dry washing',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->pushNotification($notificationCustomer);
        
        return true;
    }


}
