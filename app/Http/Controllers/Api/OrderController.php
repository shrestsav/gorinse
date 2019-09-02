<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Item;
use App\Jobs\PendingNotification;
use App\MainArea;
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
        $orders = Order::where('customer_id',Auth::id())->with('customer','pickDriver','dropDriver')->get();

        $collection = collect([
            'orders' => $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);
        return $collection;
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

            //Notify Admin if order has not been accepted in 10 Minutes
            // PendingNotification::dispatch($order->id)
            //     ->delay(now()->addSeconds(10));
                // ->addMinutes(10)

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
            $order->update([
                'driver_id' => Auth::id(), 
                'pick_assigned_by' => Auth::id(),
                'PAT' => Date('Y-m-d h:i:s'),
                'status' => 1]);
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
     * Cancel Accepted Order.
    **/
    public function cancelPickup(Request $request)
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

        $order = Order::findOrFail($request->order_id);

        if($order->status==1 && $order->driver_id==Auth::id() && $order->pick_assigned_by==Auth::id()){

            $order->update([
                // 'driver_id' => null, 
                // 'pick_assigned_by' => null,
                // 'PAT' => null,
                'status' => 0
            ]);
            User::notifyCancelForPickup($request->order_id);
            return response()->json([
                'status'=>'200',
                'message'=>'Pickup Cancelled'
            ],200);
        }
        elseif($order->status==1 && $order->driver_id==Auth::id() && $order->pick_assigned_by!=Auth::id()){
            return response()->json([
                'status'=>'403',
                'message'=>'Please contact your manager to cancel this pickup'
            ],403);
        }

        return response()->json([
                'status'=>'403',
                'message'=>'Something wrong with the request'
            ],403);
    }

    /**
     * List of pending orders for drivers of that specific area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pendingOrders()
    {
        $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->format('Y-m-d');
        $driver_area = User::find(Auth::id())->details->area_id;
        $pickPending = Order::select('orders.id',
                                     'orders.type',
                                     'orders.customer_id',
                                     'orders.pick_location',
                                     'orders.pick_date',
                                     'orders.pick_timerange',
                                     'orders.status',
                                     'orders.created_at',
                                     'pick.name as pick_location_name')
                        ->join('user_addresses as pick','orders.pick_location','=','pick.id')
                        ->where('orders.status','=',0)
                        ->where('pick.area_id','=',$driver_area)
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community')
                        ->get();

        // $map = $orders->map(function($order){
        //    $data['user_firstName'] = $order->id;
        //    $data['user_lastName'] = $order->type;
        //    return $data;
        // });

        $activeOrder = Order::select('id',
                                     'type',
                                     'customer_id',
                                     'pick_location',
                                     'pick_date',
                                     'pick_timerange',
                                     'drop_location',
                                     'drop_date',
                                     'drop_timerange',
                                     'status',
                                     'created_at')
                        ->where(function ($query){
                            $query->where('status','>=',1)
                                  ->where('status','<=',4)
                                  ->where('driver_id','=',Auth::id());
                        })
                        ->orWhere(function ($query) use ($today){
                            $query->where('status','>=',5)
                                  ->where('status','<=',6)
                                  ->whereDate('drop_date','=',$today)
                                  ->where('drop_driver_id','=',Auth::id());
                        })
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community','drop_location_details:id,name,map_coordinates,building_community')
                        ->get();


        $assignedForDrop = Order::select('id',
                                         'type',
                                         'customer_id',
                                         'pick_location',
                                         'drop_location',
                                         'drop_date',
                                         'drop_timerange',
                                         'status',
                                         'created_at')
                        ->where('status','>=',5)
                        ->where('status','<=',6)
                        ->whereDate('drop_date','!=',$today)
                        ->where('drop_driver_id','=',Auth::id())
                        ->with('customer:id,fname,lname','drop_location_details:id,name,map_coordinates,building_community')
                        ->get();

        $mainAreas = MainArea::nameWithId();
        $collection = collect([
            'active'        => $activeOrder,
            'pick'          => $pickPending,
            'drop'          => $assignedForDrop,
            'orderStatus'   => config('settings.orderStatuses'),
            'driverArea'    => $driver_area,
            'mainAreas'     => $mainAreas,
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);
        return response()->json($collection);
    }
    /**
     * List of pending orders for drivers of that specific area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function orderItems(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'order_id' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => '422',
    //             'message' => 'Validation Failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $order_id = $request->order_id;
    //     $order = Order::where('id',$order_id);
    //     if($order->exists()){
    //         if(Auth::id()==$order->first()->driver_id){
    //             foreach($request->items as $service_id => $items){
    //                 $serviceCharge = Service::find($service_id)->price;
    //                 foreach ($items as $item) {
    //                     $itemCharge = Item::find($item['item_id'])->price;
    //                     $orderItem = new OrderItem;
    //                     $orderItem->order_id = $order_id;
    //                     $orderItem->service_id = $service_id;
    //                     $orderItem->item_id = $item['item_id'];
    //                     $orderItem->quantity = $item['quantity'];
    //                     $orderItem->service_charge = $serviceCharge;
    //                     $orderItem->item_charge = $itemCharge;
    //                     $orderItem->remarks = $item['remarks'];
    //                     $orderItem->save();
    //                 }
    //             }
    //             $order->update([
    //                     'VAT' => config('settings.VAT'),
    //                     'delivery_charge' => config('settings.delivery_charge')
    //                 ]);
    //             // User::notifyInvoiceGenerated($order_id);
    //             return response()->json($this->generateInvoice($order_id));
    //         }
    //         else{
    //             return response()->json([
    //                 'status' => '403',
    //                 'message' => 'You donot have access to this order' 
    //             ],403);
    //         }
    //     }
    //     else{
    //         return response()->json([
    //             'status' => '404',
    //             'message' => 'Order doesnot exist' 
    //         ],404);
    //     }
    // }    
    /**
     * Add Items to Orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'service_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $appDefault = AppDefault::firstOrFail();
        $VAT = $appDefault->VAT;
        $deliveryCharge = $appDefault->delivery_charge;

        $order_id = $request->order_id;
        $service_id = $request->service_id;
        $serviceCharge = Service::findOrFail($service_id)->price;
        $order = Order::where('id',$order_id);
        if($order->exists()){
            if(Auth::id()==$order->first()->driver_id){
                foreach($request->items as $item){
                    $itemCharge = Item::findOrFail($item['item_id'])->price;
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
                $order->update([
                    'VAT' => $VAT,
                    'delivery_charge' => $deliveryCharge
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
    }    /**
     * Add Items to Orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItemsGenerateInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'service_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $appDefault = AppDefault::firstOrFail();
        $VAT = $appDefault->VAT;
        $deliveryCharge = $appDefault->delivery_charge;

        $order_id = $request->order_id;
        $service_id = $request->service_id;
        $serviceCharge = Service::findOrFail($service_id)->price;
        $order = Order::where('id',$order_id);
        if($order->exists()){
            if(Auth::id()==$order->first()->driver_id){
                foreach($request->items as $item){
                    $itemCharge = Item::findOrFail($item['item_id'])->price;
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
                $order->update([
                    'VAT' => $VAT,
                    'delivery_charge' => $deliveryCharge
                ]);
                // User::notifyInvoiceGenerated($order_id);
                return response()->json($this->genInvoice($order_id));
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

    //Driver Picked Clothes from office which were assigned to him for delivery to customers
    public function driverPickedFromOffice($order_id)
    {
        $order = Order::findOrFail($order_id);

        if($order->drop_driver_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You are not assigned to delivery this order',
                ], 403);
        }
        elseif($order->status==5){
            $order->status = 6;
            $order->PFO = date(config('settings.dateTime'));
            $order->save();
            // $pickDelivery = $order->update([
            //                     'status' => 6,
            //                     'PFO'    => date(config('settings.dateTime'))
            //                 ]);
            if(!$order){
                return response()->json([
                        'status' => '400',
                        'message' => 'Sorry the Order status could not be updated',
                    ], 400);
            }
            User::notifyPickedFromOffice($order_id);
            return response()->json([
                    'status' => '200',
                    'message' => 'Order Picked From Office',
                ], 200);
        }
        return response()->json([
            'status' => '400',
            'message' => 'Something is wrong with the request',
        ], 400);
    }

    public function cancelOrderForCustomer($order_id)
    {
        $order = Order::findOrFail($order_id);

        if(Auth::id()!=$order->customer_id){
            return response()->json([
                    'status' => '403',
                    'message' => 'You donot have access to this order',
                ], 403);
        }
        
        if($order->status>2){
            return response()->json([
                    'status' => '403',
                    'message' => 'Please contact Gorinse to cancel this order',
                ], 403);
        }
        $order->delete();

        $orderDetails = OrderItem::where('order_id','=',$order_id)->delete();

        return response()->json([
                    'status' => '200',
                    'message' => 'Order has been cancelled and deleted',
                ], 200);
    }

    //Works with multiple Service Ids
    public function generateInvoice($order_id)
    {
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item','customer')->first();
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
            'name' => $orderDetails->customer->fname.' '.$orderDetails->customer->lname,
            'order_type' => config('settings.orderType')[$orderDetails->type],
        ];
        $invoiceCollection = [
            "customer_details" => $other,
            "items_details" => $grouped_collection,
            "invoice_details" => $invoice,
            
        ];

        return $invoiceCollection;
    }

    //Works for single Service Id
    public function genInvoice($order_id)
    {
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item','customer')->first();
        $totalAmount = 0;
        $totalQuantity = 0;
        $invoiceArr = [];
        $serviceName = '';
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            // $serviceCharge = $item['service']['price'];
            // $itemCharge = $item['item']['price'];
            $rate = $item['service_charge']+$item['item_charge'];
            $amount = $rate*$itemQuantity;
            $totalQuantity += $itemQuantity;
            $totalAmount += $amount;

            $invoice = [
                'item' => $item['item']['name'],
                'quantity' => $itemQuantity,
                'total' => $amount,
            ];

            //Risky Business,,,, order should have only one service id for this to work properly
            $serviceName = $item['service']['name'];

            array_push($invoiceArr,$invoice);
        };
        $collection = collect($invoiceArr);
        $grouped_collection = $collection->groupBy(['service'])->toArray();       
        $vatPercent = $orderDetails->VAT;
        $VAT = ($vatPercent/100)*$totalAmount;
        $deliveryCharge = $orderDetails->delivery_charge;
        $grandTotal = $totalAmount+$VAT+$deliveryCharge;
        
        $invoice = [
            "name"            => $orderDetails->customer->fname.' '.$orderDetails->customer->lname,
            "service"         => $serviceName,
            'order_type'      => config('settings.orderType')[$orderDetails->type],
            "total_quantity"  => $totalQuantity,
            "total_amount"    => $totalAmount,
            "VAT_percent"     => $vatPercent,
            "VAT"             => $VAT,
            "delivery_charge" => $deliveryCharge,
            "grand_total"     => $grandTotal
        ];
        $invoiceCollection = [
            "items_details" => $collection,
            "invoice_details" => $invoice,
            
        ];

        return $invoiceCollection;
    }
}
