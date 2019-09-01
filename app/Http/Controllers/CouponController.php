<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::orderBy('id','DESC')->get();
        return response()->json($coupons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string',
            'status' => 'required|numeric',
            'description' => 'required',
            'type' => 'required|numeric',
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->status = $request->status;
        $coupon->type = $request->type;
        $coupon->description = $request->description;
        $coupon->save();
        
        return response()->json('Successfully Added Coupon');
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
        $validatedData = $request->validate([
            'code' => 'required|string',
            'type' => 'required|numeric',
            'description' => 'required',
            'status' => 'required|numeric',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'description' => $request->description,
            'status' => $request->status
        ]);
        
        return response()->json('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();
        return response()->json('Coupon Deleted');
    }

}
