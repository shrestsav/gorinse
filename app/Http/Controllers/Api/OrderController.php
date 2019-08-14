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

        $order = Order::find($request->order_id);

        if($order->first()->status==0){
            $order->update(['driver_id' => Auth::id(), 'status' => 2]);
            User::notifyAcceptOrder($request->order_id);
        }
        else{
            return response()->json(['error'=>'You cannot accept this order, It has already been accepted'],403);
        }
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
        $driver_area = User::find(Auth::id())->details->area_id;
        $orders = Order::select('orders.id',
                                'orders.type',
                                'orders.customer_id',
                                'orders.pick_location',
                                'orders.created_at',
                                'pick.name as pick_location_name')
                        ->join('user_addresses as pick','orders.pick_location','=','pick.id')
                        ->where('orders.status','=',0)
                        ->where('pick.area_id','=',$driver_area)
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community')
                        ->get();

        return response()->json($orders);
    }
}
