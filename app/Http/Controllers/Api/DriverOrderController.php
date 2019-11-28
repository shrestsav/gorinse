<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\MainArea;
use App\Order;
use App\User;
use Auth;
use Illuminate\Http\Request;

class DriverOrderController extends Controller
{
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

    public function active()
    {
        $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->format('Y-m-d');

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

        $collection = collect([
            'active'        => $activeOrder,
            'orderStatus'   => config('settings.orderStatuses'),
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);
        return response()->json($collection);
    } 

    public function pending()
    {
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

        $mainAreas = MainArea::nameWithId();

        $collection = collect([
            'pending'       => $pickPending,
            'orderStatus'   => config('settings.orderStatuses'),
            'driverArea'    => $driver_area,
            'mainAreas'     => $mainAreas,
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);

        return response()->json($collection);
    } 

    public function drop()
    {
        $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->format('Y-m-d'); 
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

        $collection = collect([
            'drop'          => $assignedForDrop,
            'orderStatus'   => config('settings.orderStatuses'),
            'notificationCount' => User::find(Auth::id())->unreadNotifications->count()
        ]);
        return response()->json($collection);
    } 
}
