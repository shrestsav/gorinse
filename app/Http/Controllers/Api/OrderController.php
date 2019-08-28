<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Item;
use App\Order;
use App\OrderItem;
use App\Service;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('customer_id',Auth::id())->with('customer','driver')->get();
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
        $validator = Validator::make($request->all(), [
            'type' => 'required|numeric',
            'pick_location' => 'required|numeric',
            'pick_date' => 'required',
            'pick_timerange' => 'required',
            'drop_location' => 'required|numeric',
            'payment' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $request['customer_id'] = Auth::id();
        $order = Order::create($request->all());
        
        if($order){
            User::notifyNewOrder($order->id);
            return response()->json([
                "status" => "200",
                "message" => "Order Created Successfully"
            ], 200);
        }
        
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
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::find($request->order_id);

        if($order->status==0){
            $order->update(['driver_id' => Auth::id(), 'status' => 1]);
            User::notifyAcceptOrder($request->order_id);
        }
        else{
            return response()->json([
                'status'=>'403',
                'message'=>'You cannot accept this order, It has already been accepted'
            ],403);
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

        $collection = collect([
            'image_url' => asset('files/products/'),
            'pending' => $orders
        ]);
        // $data = $collection->merge($orders);        

        // $collection = collect($orders);
        // $data = $collection->merge($orders);

        $acceptedOrders = Order::select('orders.id',
                                        'orders.type',
                                        'orders.customer_id',
                                        'orders.pick_location',
                                        'orders.created_at',
                                        'pick.name as pick_location_name')
                        ->where('orders.status','>=',1)
                        ->where('orders.status','<=',4)
                        ->where('orders.driver_id','=',$driver_area)
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community')
                        ->get();

        return response()->json($collection);
    }
    /**
     * List of pending orders for drivers of that specific area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order_id = $request->order_id;
        $order = Order::where('id',$order_id);
        if($order->exists()){
            if(Auth::id()==$order->first()->driver_id){
                foreach($request->items as $service_id => $items){
                    $serviceCharge = Service::find($service_id)->price;
                    foreach ($items as $item) {
                        $itemCharge = Item::find($item['item_id'])->price;
                        $orderItem = new OrderItem;
                        $orderItem->order_id = $order_id;
                        $orderItem->service_id = $service_id;
                        $orderItem->item_id = $item['item_id'];
                        $orderItem->quantity = $item['quantity'];
                        $orderItem->service_charge = $serviceCharge;
                        $orderItem->item_charge = $itemCharge;
                        $orderItem->remarks = $item['remarks'];
                        $orderItem->save();
                    }
                }
                $order->update([
                        'VAT' => config('settings.VAT'),
                        'delivery_charge' => config('settings.delivery_charge')
                    ]);
                // User::notifyInvoiceGenerated($order_id);
                return response()->json($this->generateInvoice($order_id));
            }
            else{
                return response()->json([
                    'status' => '403',
                    'message' => 'You donot have access to this order' 
                ],403);
            }
        }
        else{
            return response()->json([
                'status' => '404',
                'message' => 'Order doesnot exist' 
            ],404);
        }
    }

    public function sendOrderInvoiceForApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order_id = $request->order_id;
        $order = Order::where('id',$order_id);
        
        if(!$order->exists()){
            return response()->json([
                'status' => '404',
                'message' => 'Order not found',
            ], 404);
        }
        elseif($order->first()->driver_id!=Auth::id()){
            return response()->json([
                'status' => '403',
                'message' => 'You donot have permission to view invoice for this order',
            ], 403);
        }

        if($order->first()->status==1){
            $order->update([ 'status' => 2 ]);
            User::notifyInvoiceGenerated($order_id);
            return response()->json([
                    'status' => '200',
                    'message' => 'Order Invoice has been sent for approval',
                ], 200);
        }
        else{
            return response()->json([
                'status' => '403',
                'message' => 'You donot have access to this order' 
            ],403);
        }
    }

    public function test()
    {
        config(['settings.VAT' => 'America/Chicago']);
        // \Config::set('settings.VAT','fsdf');
        return config('settings.VAT');
    }    

    public function customerOrderInvoice($order_id)
    {
        $order = Order::where('id',$order_id);
        if(!$order->exists()){
            return response()->json([
                    'status' => '404',
                    'message' => 'Order not found',
                ], 404);
        }
        elseif($order->first()->customer_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You donot have permission to view invoice for this order',
                ], 403);
        }
        else{
            return response()->json($this->generateInvoice($order_id));
        }
    }

    public function customerConfirmInvoice($order_id)
    {
        $order = Order::where('id',$order_id);
        if(!$order->exists()){
            return response()->json([
                    'status' => '404',
                    'message' => 'Order not found',
                ], 404);
        }
        elseif($order->first()->customer_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You donot have permission to view invoice for this order',
                ], 403);
        }
        elseif($order->first()->status==2){
            $confirmInvoice = $order->update([
                                'status' => 3,
                                'PFC'    => date(config('settings.dateTime'))
                            ]);
            User::notifyInvoiceConfirmed($order_id);
            if(!$confirmInvoice){
                return response()->json([
                        'status' => '400',
                        'message' => 'Sorry the Order Invoice could not be confirmed',
                    ], 400);
            }
            return response()->json([
                    'status' => '200',
                    'message' => 'Order Invoice has been confirmed',
                ], 200);
        }
        return response()->json([
                    'status' => '400',
                    'message' => 'Something is wrong with the request',
                ], 400);
    }

    public function driverDropAtOffice($order_id)
    {
        $order = Order::where('id',$order_id);
        if(!$order->exists()){
            return response()->json([
                    'status' => '404',
                    'message' => 'Order not found',
                ], 404);
        }
        elseif($order->first()->driver_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You donot have permission to view this order',
                ], 403);
        }
        elseif($order->first()->status==3){
            $confirmInvoice = $order->update([
                                'status' => 4,
                                'DAO'    => date(config('settings.dateTime'))
                            ]);
            User::notifyDroppedAtOffice($order_id);
            if(!$confirmInvoice){
                return response()->json([
                        'status' => '400',
                        'message' => 'Sorry the Order status could not be updated',
                    ], 400);
            }
            return response()->json([
                    'status' => '200',
                    'message' => 'Order Status changed to On Work',
                ], 200);
        }
        return response()->json([
                    'status' => '400',
                    'message' => 'Something is wrong with the request',
                ], 400);
    }

    public function generateInvoice($order_id)
    {
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item')->first();
        $totalAmount = 0;
        $totalQuantity = 0;
        $invoiceArr = [];
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            // $serviceCharge = $item['service']['price'];
            // $itemCharge = $item['item']['price'];
            $rate = $item['service_charge']+$item['item_charge'];
            $amount = $rate*$itemQuantity;
            $totalQuantity += $itemQuantity;
            $totalAmount += $amount;

            $invoice = [
                'service' => $item['service']['name'],
                'item' => $item['item']['name'],
                'quantity' => $itemQuantity,
                'total' => $amount,
            ];

            array_push($invoiceArr,$invoice);
        };
        $collection = collect($invoiceArr);
        $grouped_collection = $collection->groupBy(['service'])->toArray();       
        $vatPercent = $orderDetails->VAT;
        $VAT = ($vatPercent/100)*$totalAmount;
        $deliveryCharge = $orderDetails->delivery_charge;
        $grandTotal = $totalAmount+$VAT+$deliveryCharge;
        
        $invoice = [
            "total_quantity" => $totalQuantity,
            "total_amount" => $totalAmount,
            "VAT_percent"  => $vatPercent,
            "VAT" => $VAT,
            "delivery_charge" => $deliveryCharge,
            "grand_total" => $grandTotal
        ];
        $other = [
            'name' => 'Utsav Shrestha',
            'order_type' => config('settings.orderType')[$orderDetails->type],
        ];
        $invoiceCollection = [
            "customer_details" => $other,
            "items_details" => $grouped_collection,
            "invoice_details" => $invoice,
            
        ];

        return $invoiceCollection;
    }
}
