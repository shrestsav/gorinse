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
            'code' => 'required|unique:coupons|string|min:7|max:7',
            'status' => 'required|numeric',
            'description' => 'required',
            'discount' => 'required|numeric',
            'type' => 'required|numeric',
            'coupon_type' => 'required|numeric',
            'valid_from_to' => 'required',
        ]);

        $coupon = new Coupon();
        $coupon->code = strtoupper($request->code);
        $coupon->status = $request->status;
        $coupon->type = $request->type;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->discount = $request->discount;
        $coupon->description = $request->description;
        $coupon->valid_from = $request->valid_from_to[0];
        $coupon->valid_to = $request->valid_from_to[1];
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
            // 'code' => 'required|unique:coupons|string|min:7|max:7',
            // 'type' => 'required|numeric',
            'description' => 'required',
            // 'discount' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            // 'code' => strtoupper($request->code),
            // 'type' => $request->type,
            // 'discount' => $request->discount,
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
