<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Http\Controllers\Controller;
use App\Item;
use App\MainArea;
use App\Order;
use App\OrderDetail;
use App\OrderItem;
use App\Service;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DriverOrderController extends Controller
{

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

        $order = Order::findOrFail($request->order_id);

        if($order->status==0){
            $order->update([
                'driver_id' => Auth::id(), 
                'status' => 1]);
            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'PAB' => Auth::id(),
                    'PAT' => Date('Y-m-d h:i:s'),
                ]
            );
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

        if($order->status==1 && $order->driver_id==Auth::id() && $order->details->PAB==Auth::id()){

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
        elseif($order->status==1 && $order->driver_id==Auth::id() && $order->details->PAB!=Auth::id()){
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderListForDriver()
    {
        $rows = AppDefault::firstOrFail()->app_rows;
        $orders = Order::select('id',
                                'customer_id',
                                'driver_id',
                                'drop_driver_id',
                                'type',
                                'status',
                                'pick_location',
                                'drop_location',
                                'created_at')
                        //Driver assigned for both pick and drop
                        ->where(function ($query){
                            $query->where('driver_id','=',Auth::id())
                                  ->where('drop_driver_id','=',Auth::id())
                                  ->whereNotIn('status',[0,1,2,3,5,6]);
                        })
                        //Driver assigned for pick only
                        ->orWhere(function ($query){
                            $query->where('driver_id','=',Auth::id())
                                  ->where(function ($query){
                                      $query->where('drop_driver_id','!=',Auth::id())
                                            ->orWhereNull('drop_driver_id');
                                  })
                                  ->where('status','>=',4);
                        })
                        //Driver assigned for drop only
                        ->orWhere(function ($query){
                            $query->where('driver_id','!=',Auth::id())
                                  ->where('drop_driver_id','=',Auth::id())
                                  ->whereNotIn('status',[5,6]);
                        })
                       ->with('customer:id,fname,lname,phone',
                              'pick_location_details:id,name',
                              'drop_location_details:id,name',
                              'details:order_id,DAO,DTC')
                       ->orderBy('created_at','DESC')
                       ->simplePaginate($rows)
                       ->makeVisible('assigned_status');

        $collection = collect([
            'orders' => $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);
        return $collection;
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
                                     'orders.driver_id',
                                     'orders.drop_driver_id',
                                     'orders.pick_location',
                                     'orders.pick_date',
                                     'orders.pick_timerange',
                                     'orders.status',
                                     'orders.created_at',
                                     'pick.name as pick_location_name')
                        ->join('user_addresses as pick','orders.pick_location','=','pick.id')
                        ->where('orders.status','=',0)
                        ->where('pick.area_id','=',$driver_area)
                        ->with('customer:id,fname,lname',
                            'pick_location_details:id,name,map_coordinates,building_community')
                        ->orderBy('created_at','DESC')
                        ->get()
                        ->makeVisible('assigned_status');

        // $map = $orders->map(function($order){
        //    $data['user_firstName'] = $order->id;
        //    $data['user_lastName'] = $order->type;
        //    return $data;
        // });

        $activeOrder = Order::select('id',
                                     'type',
                                     'customer_id',
                                     'driver_id',
                                     'drop_driver_id',
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
                                  ->where('status','<=',3)
                                  ->where('driver_id','=',Auth::id());
                        })
                        ->orWhere(function ($query) use ($today){
                            $query->where('status','>=',5)
                                  ->where('status','<=',6)
                                  ->whereDate('drop_date','=',$today)
                                  ->where('drop_driver_id','=',Auth::id());
                        })
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community','drop_location_details:id,name,map_coordinates,building_community')
                        ->orderBy('updated_at','DESC')                        
                        ->get()
                        ->makeVisible('assigned_status');


        $assignedForDrop = Order::select('id',
                                         'type',
                                         'customer_id',
                                         'driver_id',
                                         'drop_driver_id',
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
                        ->orderBy('drop_date','ASC')
                        ->get()
                        ->makeVisible('assigned_status');

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

    public function pickOrders()
    {
        $driver_area = User::find(Auth::id())->details->area_id;
        $mainAreas = MainArea::nameWithId();

        $pickOrders = Order::select('id',
                                     'type',
                                     'customer_id',
                                     'driver_id',
                                     'drop_driver_id',
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
                                  ->where('status','<=',3)
                                  ->where('driver_id','=',Auth::id());
                        })
                        ->with('customer:id,fname,lname','pick_location_details:id,name,map_coordinates,building_community','drop_location_details:id,name,map_coordinates,building_community')
                        ->orderBy('updated_at','DESC')                        
                        ->get()
                        ->makeVisible('assigned_status');

        $collection = collect([
            'pickOrders'        => $pickOrders,
            'orderStatus'       => config('settings.orderStatuses'),
            'driverArea'        => $driver_area,
            'mainAreas'         => $mainAreas
        ]);

        return response()->json($collection);
    } 

    public function newOrders()
    {
        $driver_area = User::find(Auth::id())->details->area_id;
        $mainAreas = MainArea::nameWithId();

        $newOrders = Order::select('orders.id',
                                     'orders.type',
                                     'orders.customer_id',
                                     'orders.driver_id',
                                     'orders.drop_driver_id',
                                     'orders.pick_location',
                                     'orders.pick_date',
                                     'orders.pick_timerange',
                                     'orders.status',
                                     'orders.created_at',
                                     'pick.name as pick_location_name')
                        ->join('user_addresses as pick','orders.pick_location','=','pick.id')
                        ->where('orders.status','=',0)
                        ->where('pick.area_id','=',$driver_area)
                        ->with('customer:id,fname,lname',
                            'pick_location_details:id,name,map_coordinates,building_community')
                        ->orderBy('created_at','DESC')
                        ->get()
                        ->makeVisible('assigned_status');

        $collection = collect([
            'newOrders'         => $newOrders,
            'orderStatus'       => config('settings.orderStatuses'),
            'driverArea'        => $driver_area,
            'mainAreas'         => $mainAreas
        ]);

        return response()->json($collection);
    } 

    public function dropOrders()
    {
        $driver_area = User::find(Auth::id())->details->area_id;
        $mainAreas = MainArea::nameWithId();
        
        $dropOrders = Order::select('id',
                                    'type',
                                    'customer_id',
                                    'driver_id',
                                    'drop_driver_id',
                                    'pick_location',
                                    'drop_location',
                                    'drop_date',
                                    'drop_timerange',
                                    'status',
                                    'created_at')
                        ->where('status','>=',5)
                        ->where('status','<=',6)
                        ->where('drop_driver_id','=',Auth::id())
                        ->with('customer:id,fname,lname','drop_location_details:id,name,map_coordinates,building_community')
                        ->orderBy('status','DESC')
                        ->orderBy('drop_date','ASC')
                        ->get()
                        ->makeVisible('assigned_status');

        $collection = collect([
            'dropOrders'        => $dropOrders,
            'orderStatus'       => config('settings.orderStatuses'),
            'driverArea'        => $driver_area,
            'mainAreas'         => $mainAreas
        ]);

        return response()->json($collection);
    } 

    public function counts()
    {
        $driver_area = User::find(Auth::id())->details->area_id;

        $newOrdersCount = Order::join('user_addresses as pick','orders.pick_location','=','pick.id')
                                ->where('orders.status','=',0)
                                ->where('pick.area_id','=',$driver_area)
                                ->get()
                                ->count();

        $dropOrdersCount = Order::where('status','>=',5)
                                ->where('status','<=',6)
                                ->where('drop_driver_id','=',Auth::id())
                                ->get()
                                ->count();

        $pickOrdersCount = Order::where(function ($query){
                                    $query->where('status','>=',1)
                                          ->where('status','<=',3)
                                          ->where('driver_id','=',Auth::id());
                                    })                       
                                ->get()
                                ->count();

        $collection = collect([
            'newOrdersCount'    => $newOrdersCount,
            'pickOrdersCount'   => $pickOrdersCount,
            'dropOrdersCount'   => $dropOrdersCount,
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);

        return response()->json($collection);
    } 

    /**
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
        $order = Order::findOrFail($order_id);
        
        if($order->driver_id != Auth::id() || ($order->status != 1 && $order->status != 2)){
            return response()->json([
                'status' => '403',
                'message' => 'You donot have access to add items for this order' 
            ],403);
        }

        //Delete previous items for that order if exists
        $items = OrderItem::where('order_id',$order_id)->delete();
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
        return response()->json($order->generateInvoiceForUser());
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
        $order = Order::findOrFail($order_id);

        if($order->driver_id!=Auth::id()){
            return response()->json([
                'status' => '403',
                'message' => 'You donot have permission to view invoice for this order',
            ], 403);
        }

        if(($order->status==1 || $order->status==2) && $order->VAT && $order->delivery_charge){
            $order->update([ 'status' => 2 ]);
            if($request->remarks){
                $orderDetails = OrderDetail::updateOrCreate(
                    ['order_id' => $order_id],
                    ['PDR'      => $request->remarks]
                );
            }
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
                                'status' => 4
                            ]);
            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order_id],
                ['DAO'      => date(config('settings.dateTime'))]
            );
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
            $order->save();

            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order_id],
                ['PFO'      => date(config('settings.dateTime'))]
            );
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

    //Driver Picked Clothes from office which were assigned to him for delivery to customers
    public function deliveredToCustomer($order_id)
    {
        $order = Order::findOrFail($order_id);

        if($order->drop_driver_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You are not assigned to delivery this order',
                ], 403);
        }
        elseif($order->status==6){
            $order->status = 7;
            $order->save();

            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order_id],
                ['DTC'      => date(config('settings.dateTime'))]
            );

            if(!$order){
                return response()->json([
                        'status' => '400',
                        'message' => 'Sorry the Order status could not be updated',
                    ], 400);
            }
            User::notifyDroppedAtCustomer($order_id);
            return response()->json([
                    'status' => '200',
                    'message' => 'Order Dropped at Customer',
                ], 200);
        }
        return response()->json([
            'status' => '400',
            'message' => 'Something is wrong with the request',
        ], 400);
    }

    public function driverOrderInvoice($order_id)
    {
        $order = Order::findOrFail($order_id);

        if($order->driver_id!=Auth::id() && $order->drop_driver_id!=Auth::id()){
            return response()->json([
                    'status' => '403',
                    'message' => 'You donot have permission to view invoice for this order',
                ], 403);
        }
        elseif($order->status < 2){
            return response()->json([
                    'status' => '403',
                    'message' => 'Invoice not generated for this order yet',
                ], 403);
        }
        else{
            return response()->json($order->generateInvoiceForUser());
        }
    }
}
