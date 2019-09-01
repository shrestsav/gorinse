<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\OTPNotification;
use App\Role;
use App\User;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Auth;

class AuthController extends Controller
{
    public function phoneRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // $request['OTP'] = rand(1000,9999);
        $request['OTP'] = 1111;
        $request['OTP_timestamp'] = Date('Y-m-d H:i:s');

        //Check if already exists
        $check = User::where('phone','=',$request->phone);

        //If Exists login otherwise register as new customer
        if($check->exists()){
            $check->update([
                        'OTP' => $request['OTP'],
                        'OTP_timestamp' => $request['OTP_timestamp']
                    ]);
            return response()->json(['message'=>'OTP has been send to your phone','user_status' => 'existing','code'=>$request['OTP']]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'phone'=> 'unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => '422',
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $customer = User::create($request->all());
            $role_id = Role::where('name','customer')->first()->id;

            //Assign User as Customer
            $customer->attachRole($role_id);

            return response()->json([
                        'message'=>'OTP has been send to your phone',
                        'user_status' => 'new',
                        'code'=>$request['OTP']
                    ]);
        }
        
        // $customer->sendOTP();

    }
    
    public function login(Request $request)
    {
        return Date('Y-m-d H:i:s');
        $validatedData = $request->validate([
            'phone' => 'required|max:55',
        ]);

        $OTP = rand(1000,9999);
        $OTP_timestamp = Date();

        //Check if User Exists
        $customer = User::where('phone',$phone);

        if($customer->exists()){
            $customer->update(['OTP' => $OTP, 'OTP_timestamp' => $OTP_timestamp]);
            return response()->json([
                'status' => '200',
                'message'=>'OTP has been send to your phone'
            ],200);
        }
        else{
            return response()->json([
                        'status' => '404',
                        'message' => 'User doesnot exist, Please register first'
                    ], Response::HTTP_NOT_FOUND);
        }
    }

    public function verifyOTP(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'phone'=> 'required',
            'OTP'=> 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = '';
        $user_id = '';
        $user_details = false;
        $url = url('').'/oauth/token';
        $OTP = $request->OTP;
        $phone = $request->phone;
        $user = User::where('phone',$phone);
        if($user->exists()){
            $user_id = $user->first()->id;
            $role = $user->first()->roles()->first()->name;
            $details = $user->first()->fname;
            if($details)
                $user_details = true;
        }
        $http = new \GuzzleHttp\Client();
        $response = $http->post(url('').'/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => 'wNfEEpIS6VUHVZKVhgUWGNjcNTUvkd5gGtsnCgnb',
                        'username' => $phone,
                        'password' => $OTP,
                        'scope' => '',
                    ],
                    'http_errors' => true // add this to return errors in json
                ]);

        $token_response = json_decode((string) $response->getBody(), true);

        $result = [
            'tokens' => $token_response,
            'role' =>$role,
            'user_id' =>$user_id,
            'user_details' =>$user_details,
        ];
        return response()->json($result);
    }

    public function createProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userInput = $request->only('fname', 'lname', 'email');
        $userDetailsInput = $request->only('referred_by');

        if($userDetailsInput['referred_by']){
            $check = UserDetail::where('referral_id',$userDetailsInput['referred_by']);
            if(!$check->exists())
                return response()->json([
                    'status' => '404',
                    'message'=> 'Referral ID is Invalid' 
                ],404);
        }

        $address = User::where('id',Auth::id())->update($userInput);
        
        //Generate random Referral ID for registered user
        $random_string = substr($request->fname, 0, 3).rand(100,999).Str::random(10);
        $referral_id = strtoupper(substr($random_string, 0, 8));
        
        //Save User Photo 
        $userDetail = UserDetail::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'referred_by' => $userDetailsInput['referred_by'],
                    'referral_id' => $referral_id
                ]);

        return response()->json([
                'status' => '200',
                'message'=> 'Profile Created Successfully' 
            ],200);
    }
    public function checkRole()
    {
        $role = Auth::user()->roles()->first()->name;

        return response()->json([
            'user_id' => Auth::id(),
            'role' => $role
        ]);
    }
    public function sendOTP()
    {
       return User::find(14)->sendOTP();
    }

    public function test()
    {
        $address = [
            [
                'name' => 'bharatpur',
                'location' => '19.123.4324.123.1234',
                'Block' => 'D'
            ],
            [
                'name' => 'Chitwan',
                'location' => '66.112.432.123.132',
                'Block' => 'E'
            ],
        ];
        UserDetail::where('id',1)->update(['address' => json_encode($address,true)]);
        return json_decode(UserDetail::find(1)->address);
        return url('').'/oauth/token';
        // return 'something';
        $http = new \GuzzleHttp\Client();
        // $request = $http->get('google.com');
        // return $request->getBody();
        $response = $http->post('http://go.rinse/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => 'wNfEEpIS6VUHVZKVhgUWGNjcNTUvkd5gGtsnCgnb',
                'username' => '+9779808224917',
                'password' => '3067',
                'scope' => '',
            ],
        ]);

        // return $response;
        return json_decode((string) $response->getBody(), true);




        // $getUnregisteredUser = User::where('id',1)->first();
        // $accessToken = $getUnregisteredUser->createToken('manual token');
        // $accessToken = $getUnregisteredUser->createToken('manual token')->accessToken;
        // return $accessToken;
    }


    public function notifications()
    {
        return response()->json(User::find(Auth::id())->unreadNotifications);
    }

    public function countUnreadNotifications()
    {
        return response()->json(User::find(Auth::id())->unreadNotifications->count());
    }

    public function markAllAsRead()
    {
        $user = User::find(Auth::id());

        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json([
            'status' => '200',
            'message'=>'All Notifications Marked as read'
        ],200);
    }

}
