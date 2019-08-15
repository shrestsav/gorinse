<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Order;
use App\OrderItem;
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
     * List of pending orders for drivers of that specific area.
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
    /**
     * List of pending orders for drivers of that specific area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderItems(Request $request)
    {
        $order_id = $request->order_id;
        return $this->generateInvoice($order_id);
        foreach($request->items as $service_id => $items){
            foreach ($items as $item) {
                $orderItem = new OrderItem;
                $orderItem->order_id = $order_id;
                $orderItem->service_id = $service_id;
                $orderItem->item_id = $item['item_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->remarks = $item['remarks'];
                $orderItem->save();
            }
        }
        return response()->json(['message','Order Items saved']);
    }
    public function test()
    {
        return Order::with('orderItems.service','orderItems.item')->get();
        return response()->json(['message','Order Items saved']);
    }
    public function generateInvoice($order_id)
    {
        $service_ids = OrderItem::where('order_id',$order_id)->groupBy('service_id')->pluck('service_id')->toArray();
        return $service_ids;
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item')->first();
        // return $orderDetails;
        $grandTotal = 0;
        $invoiceArr = [];
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            $serviceCharge = $item['service']['price'];
            $itemCharge = $item['item']['price'];
            $total = ($itemCharge+$serviceCharge)*$itemQuantity;
            $grandTotal += $total;

            $invoice = [
                'service' => $item['service']['name'],
                'item' => $item['item']['name'],
                'total' => $total,
            ];

            array_push($invoiceArr,$invoice);
        };
        $invoiceCollection = [
            'invoice_details' => $invoiceArr,
            'grand_total' => $grandTotal
        ];
        return $invoiceCollection;
    }
}
