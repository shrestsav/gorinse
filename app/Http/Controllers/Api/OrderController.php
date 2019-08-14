<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Order;
use App\User;
use Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('customer','driver')->get();
        return new OrderCollection($orders);
        return $orders;
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

    /**
     * Accept pending orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acceptOrder(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required',
        ]);

        $order = Order::where('id','=',$request->order_id);

        if($order->first()->status==0){
            $order->update(['driver_id' => Auth::id(), 'status' => 2]);
        }
        else{
            return response()->json(['error'=>'You cannot accept this order, It has already been accepted'],401);
        }
        User::find(1)->pushNotification();
        return response()->json(['message' => 'Successfully Accepted']);
    }

    /**
     * List of pending orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pendingOrders()
    {
        $orders = Order::where('status','=',0)->get();

        return response()->json($orders);
    }
}
