<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\Order;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $orders = Order::with('customer','pickDriver','dropDriver')->get();
        return new OrderCollection($orders);
        return $orders;
    }

    public function getOrders($status)
    {
        $rows = Session::get('rows');

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
                        ->with('customer','pickDriver','pick_location_details','drop_location_details','orderItems','dropDriver')
                        ->orderBy('status','ASC')
                        ->paginate($rows);
                        
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
                        ->with('customer','pickDriver','pick_location_details','drop_location_details')
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
            'type' => 'required',
        ]);

        if($request->type=='pickAssign'){
            // $validatedData = $request->validate([
            //     'pick_date' => 'required',
            //     'pick_timerange' => 'required',
            // ]);
            $assign = Order::where('id','=',$request->order_id)
                            ->update([
                                'driver_id' => $request->driver_id, 
                                'pick_assigned_by' => Auth::id(),
                                // 'pick_date' => $request->pick_date,
                                // 'pick_timerange' => $request->pick_timerange,
                                'PAT' => Date('Y-m-d h:i:s'),
                                'status' => 1
                            ]);
            User::notifyAssignedForPickup($request->order_id);      
        }
        if($request->type=='dropAssign'){
            $validatedData = $request->validate([
                'drop_date' => 'required',
                'drop_timerange' => 'required',
            ]);
            $assign = Order::where('id','=',$request->order_id)
                            ->update([
                                'drop_driver_id' => $request->driver_id, 
                                'drop_assigned_by' => Auth::id(),
                                'drop_date' => $request->drop_date,
                                'drop_timerange' => $request->drop_timerange,
                                'DAT' => Date('Y-m-d h:i:s'),
                                'status' => 5
                            ]);
            User::notifyAssignedForDelivery($request->order_id);
        }

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
