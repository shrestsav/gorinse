<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UserDetail;
use Illuminate\Http\Request;
use Validator;
use Auth;

class DriverController extends Controller
{
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
