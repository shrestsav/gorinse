<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::id();
        return Order::with('customer','driver')->get();
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

        $orders = Order::whereIn('status',$statusArr)->with('customer','driver')->paginate(5);
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
    // public function show($id)
    // {
    //     //
    // }
    public function show(Order $order)
    {
        return new OrderResource($order);
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

        $assign = Order::where('id','=',$request->order_id)->update(['driver_id' => $request->driver_id, 'status' => 2]);

        return response()->json('Successfully Assigned');
    }
}
