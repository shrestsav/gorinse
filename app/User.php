<?php

namespace App;

use App\Notifications\OTPNotification;
use App\Notifications\SystemNotification;
use App\Traits\NotificationLogics;
use App\Order;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use NotificationLogics;
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

    protected $appends = ['full_name'];

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

    public function routeNotificationForTwilio()
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
                       })
                        ->with('details')
                        ->orderBy('id','DESC')
                        ->get();

        return $drivers;
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

    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
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

    /**
     * Send Push Notifications
     *
     * @param  array  $notification
     * @return boolean
     */
    public function pushNotification($notification)
    {   
        $this->notify(new SystemNotification($notification));
    }

    public function tok()
    {
        // foreach($userTokens as $token) {
        //     $token->revoke();   
        // }
        // return $userTokens;

        $collection = collect([
            'tokens' => $this->tokens,
            'active' => Auth::user()->token()
        ]);

        return $collection;
    }


}
