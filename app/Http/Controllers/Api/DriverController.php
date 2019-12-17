<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Http\Controllers\Controller;
use App\User;
use App\UserDetail;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DriverController extends Controller
{
    public function index()
    {
        $driver = User::select('id',
                               'fname',
                               'lname',
                               'phone',
                               'email',
                               'created_at')
                        ->where('id',Auth::id())
                        ->with('details:user_id,description,photo,area_id',
                               'details.driverMainArea:id,name')
                        ->first();
        if($driver->details->photo)
            $driver->details->photo = asset('files/users/'.Auth::id().'/'.$driver->details->photo);
        else
            $driver->details->photo = asset('files/users/no_photo.png');

        $appDefaults = AppDefault::first();
        $driverNotes = $appDefaults->driver_notes;
        
        $collection = collect([
            'driver'  => $driver,
            'driverNotes' => $driverNotes,
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);
        
        return response()->json($collection);
    }

    public function changeMainArea(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $updateMainArea = UserDetail::where('user_id', '=', Auth::id())->firstOrFail();
        
        $updateMainArea->update([
			'area_id' => $request->area_id
		]);
    	
    	return response()->json([
                "status" => "200",
                "message" => "Main Area Updated"
            ], 200);
    }
}
