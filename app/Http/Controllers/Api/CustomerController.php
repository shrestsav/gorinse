<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserAddress;
use App\UserDetail;
use Illuminate\Http\Request;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = User::select('id','fname','lname','phone','email','created_at','updated_at')->where('id',Auth::id())->with('details','addresses')->first();
        return response()->json($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'fname' => 'required',
        ]);
        $input = $request->only('fname', 'lname', 'email');
        $address = User::where('id',Auth::id())->update($input);
        
        //Save User Photo 
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = 'dp_user_'.Auth::id().'.'.$photo->getClientOriginalExtension();
            $uploadDirectory = public_path('files'.DS.'users'.DS.Auth::id());
            $photo->move($uploadDirectory, $fileName);

            $userDetail = UserDetail::updateOrCreate(
                ['user_id' => Auth::id()],
                ['photo' => $fileName]
            );
        } 
        return response()->json([
                'status' => '201',
                'message'=> 'Profile Updated Successfully' 
            ],201);
    }

    public function changePhone(Request $request)
    {
        $validatedData = $request->validate([
            'newPhone' => 'required',
        ]);
        $OTP = rand(1000,9999);
        $OTP_timestamp = Date('Y-m-d H:i:s');
        User::where('id',Auth::id())->update([
                        'OTP' => $OTP,
                        'OTP_timestamp' => $OTP_timestamp
                    ]);
        return response()->json([
                'status' => '200',
                'message'=> 'OTP has been sent to your phone', 
                'OTP'=> $OTP, 
            ],200);
    }

    public function updatePhone(Request $request)
    {
        $validatedData = $request->validate([
            'newPhone' => 'required',
            'OTP' => 'required|numeric',
        ]);
        $user = User::find(Auth::id());
        $OTP_timestamp = \Carbon\Carbon::parse($user->OTP_timestamp);
        $current = \Carbon\Carbon::now();
        $totalTime = \Carbon\Carbon::now()->diffInMinutes($OTP_timestamp);

        if($user->OTP==$request->OTP && $totalTime<=config('settings.OTP_expiry')){
            User::where('id',Auth::id())->update([
                        'phone' => $request->newPhone,
                    ]);
            return response()->json([
                'status' => '200',
                'message'=> 'Phone Number has been updated successfully', 
            ],200);
        }
        else{
            return response()->json([
                'status' => '403',
                'message'=> 'OTP did not match', 
            ],403);
        }
        
    }

    public function getAddress()
    {
        $address = UserAddress::where('user_id',Auth::id())->get();
        return response()->json($address);
    }
    
    public function addAddress(Request $request)
    {
        unset($request['map_coordinates']);

        $validatedData = $request->validate([
            'area_id' => 'required|numeric',
            'type' => 'required|numeric',
        ]);
        
        $request['user_id'] = Auth::id(); 

        if($request->lattitude && $request->longitude){
            $coordinates = [
                'lattitude' => $request->lattitude,
                'longitude' => $request->longitude
            ];
            $map_coordinates = json_encode($coordinates);
            $request['map_coordinates'] = $map_coordinates;
        }

        $address = UserAddress::create($request->all());
        return response()->json($address);
    }    

    public function updateAddress(Request $request)
    {
        unset($request['map_coordinates']);
        $validatedData = $request->validate([
            'address_id' => 'required|numeric',
        ]);
        $map_coordinates = null; 
        if($request->lattitude && $request->longitude){
            $coordinates = [
                'lattitude' => $request->lattitude,
                'longitude' => $request->longitude
            ];
            $map_coordinates = json_encode($coordinates);
        }
        $address = UserAddress::where('id',$request->address_id)->where('user_id',Auth::id());
        if($address->exists()){
            $address->update([
                        'name' => $request->name,
                        'area_id' => $request->area_id,
                        'map_coordinates' => $map_coordinates,
                        'building_community' => $request->building_community,
                        'type' => $request->type,
                        'appartment_no' => $request->appartment_no,
                        'remarks' => $request->remarks,
                    ]);
            return response()->json([
                'status' => '201',
                'message'=> 'Address Updated Successfully' 
            ],201);
        }
        else{
            return response()->json([
                'status' => '403',
                'message'=> 'Address doesnot exist' 
            ],403);
        }
        
    }
}
