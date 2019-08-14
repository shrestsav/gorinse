<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserAddress;
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
        $customer = User::select('id','fname','lname','phone','created_at','updated_at')->where('id',Auth::id())->with('details','addresses')->first();
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

    public function getAddress()
    {
        $user_id = Auth::id();
        $address = UserAddress::where('user_id',$user_id)->get();
        return response()->json($address);
    }
    
    public function addAddress(Request $request)
    {
        $validatedData = $request->validate([
            'area_id' => 'required|numeric',
            'type' => 'required|numeric',
        ]);
        $request['user_id'] = Auth::id();
        $address = UserAddress::create($request->all());
        return response()->json($address,201);
    }
}
