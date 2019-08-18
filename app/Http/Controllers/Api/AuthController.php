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
        $request['OTP'] = rand(1000,9999);
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
            $validatedData = $request->validate([
                'phone'=> 'required|unique:users',
            ]);
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
            return response()->json(['message'=>'OTP has been send to your phone']);
        }
        else{
            return response()->json([
                                    'errors' => 'User doesnot exist, Please register first'
                                ], Response::HTTP_NOT_FOUND);
        }
    }

    public function verifyOTP(Request $request)
    {   
        $validatedData = $request->validate([
            'phone'=> 'required',
            'OTP'=> 'required|numeric',
        ]);
        $role = '';
        $user_id = '';
        $url = url('').'/oauth/token';
        $OTP = $request->OTP;
        $phone = $request->phone;
        $user = User::where('phone',$phone);
        if($user->exists()){
            $user_id = $user->first()->id;
            $role = $user->first()->roles()->first()->name;

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
        ];
        return response()->json($result);

        // $getUnregisteredUser = User::where('phone',$phone)->first();

        //Check if the OTP is correct
        // if($getUnregisteredUser->OTP==$OTP){
        //     $accessToken = $getUnregisteredUser->createToken('authToken hola')->accessToken;
        //     return response()->json(['user'=>$getUnregisteredUser,'access_token'=>$accessToken]);
        // }
        // else{
        //     return response()->json([
        //                             'errors' => 'Sorry OTP doesnot match, try resending',
        //                             'db_otp' => $getUnregisteredUser->OTP,
        //                             'post_otp'=> $OTP,
        //                         ], Response::HTTP_NOT_FOUND);
        // }
    }

    public function createProfile(Request $request)
    {
        $validatedData = $request->validate([
            'fname' => 'required'
        ]);
        $userInput = $request->only('fname', 'lname', 'email');
        $userDetailsInput = $request->only('referred_by');
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
                'status' => '201',
                'message'=> 'Profile Updated Successfully' 
            ],201);
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

}
