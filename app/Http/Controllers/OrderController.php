<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\User;
use App\Order;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function getOrders($status)
    {
        $statusArr = [];

        if($status==='Pending')
          $statusArr = ['0','1','2','3'];
        else if($status==='Received')
          $statusArr = ['4'];
        else if($status==='Ready for Delivery')
          $statusArr = ['5'];
        else if($status==='On Hold')
          $statusArr = ['6','7'];
        else if($status==='Completed')
          $statusArr = ['8'];

        $orders = Order::whereIn('status',$statusArr)
                        ->with('customer','driver','pick_location_details','drop_location_details','orderItems')
                        ->orderBy('status','ASC')
                        ->paginate(config('settings.rows'));
                        
        return response()->json($orders);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'customer_id' => 'required|numeric',
            'type' => 'required|numeric',
        ]);
        $order = Order::create($request->all());

        if($order)
            return response()->json('Order Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = 2;
        $order = Order::where('id',$id)
                        ->with('customer','driver','pick_location_details','drop_location_details')
                        ->first();
        $invoice = Order::generateInvoice($id);

        $response = [
            'details' => $order,
            'invoice' => $invoice
        ];
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    public function assignOrder(Request $request)
    {
        $validatedData = $request->validate([
            'driver_id' => 'required',
            'order_id' => 'required',
        ]);

        $assign = Order::where('id','=',$request->order_id)
                        ->update([
                            'driver_id' => $request->driver_id, 
                            'status' => 1
                        ]);

        return response()->json('Successfully Assigned');
    }

    public function testNotification($user_id)
    {
        // $user = User::find($user_id)->pushNotification();
        $order = Order::find(1);
        $test =  User::notifyAcceptOrder($order);
        return 'done';

    }
}
