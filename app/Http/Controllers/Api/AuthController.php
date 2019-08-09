<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\OTPNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function reg(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'phone'=> 'required|unique:users',
        ]);
        $request['OTP'] = rand(1000,9999);
        $request['OTP_timestamp'] = date('y-m-d hh:mm:ss');

        //Check if already exists
        $check = User::where('phone',$request->phone);
        if($check->exists()){
            $check->update([
                            'OTP' => $request['OTP']
                        ]);
        }
        else{
            $customer = User::create($request->all());
            $customer->sendOTPs();
        }
        
        return response()->json(['message'=>'OTP has been send to your phone']);

    }
    
    public function login(Request $request)
    {
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
        $OTP = $request->OTP;
        $phone = $request->phone;
        $getUnregisteredUser = User::where('phone',$phone)->first();

        //Check if the OTP is correct
        if($getUnregisteredUser->OTP==$OTP){
            $accessToken = $getUnregisteredUser->createToken('authToken hola')->accessToken;
            return response()->json(['user'=>$getUnregisteredUser,'access_token'=>$accessToken]);
        }
        else{
            return response()->json([
                                    'errors' => 'Sorry OTP doesnot match, try resending'
                                ], Response::HTTP_NOT_FOUND);
        }
    }

    public function sendOTP()
    {
       return  User::find(14)->sendOTPs();
    }

}
