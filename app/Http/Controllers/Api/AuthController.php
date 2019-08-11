<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\OTPNotification;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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

            return response()->json(['message'=>'OTP has been send to your phone','user_status' => 'new','code'=>$request['OTP']]);
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

        $url = url('').'/oauth/token';
        $OTP = $request->OTP;
        $phone = $request->phone;
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
                    'http_errors' => false // add this to return errors in json
                ]);

        $token_response = json_decode((string) $response->getBody(), true);
        return $token_response;









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

    public function sendOTP()
    {
       return User::find(14)->sendOTP();
    }

    public function test()
    {
        
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
