<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Jobs\PendingNotification;
use App\Mail\notifyMail;
use App\Order;
use App\OrderDetail;
use App\OrderItem;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Validation\Rule;
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
        $rows = AppDefault::firstOrFail()->app_rows;

        $orders = Order::where('customer_id',Auth::id())
                       ->with('customer','pickDriver','dropDriver')
                       ->orderBy('created_at','DESC')
                       ->simplePaginate($rows);

        $collection = collect([
            'orders' => $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);

        return $collection;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeOrderListCustomer()
    {
        $rows = AppDefault::firstOrFail()->app_rows;

        $orders = Order::select('id','driver_id','pick_driver_id','type','status','pick_location','drop_location','created_at')
                       ->where('customer_id',Auth::id())
                       ->with('pick_location_details','drop_location_details','pickDriver','dropDriver')
                       ->where('status','<',7)
                       ->orderBy('created_at','DESC')
                       ->simplePaginate($rows);

        $collection = collect([
            'orders'      => $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);

        return $collection;
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deliveredOrderListCustomer()
    {
        $rows = AppDefault::firstOrFail()->app_rows;
        $orders = Order::select('id','type','status','created_at')
                       ->where('customer_id',Auth::id())
                       ->with('details:id,order_id,DTC','pick_location_details','drop_location_details')
                       ->where('status','>=',7)
                       ->orderBy('created_at','DESC')
                       ->simplePaginate($rows);

        $collection = collect([
            'orders' => $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);
        return $collection;
    }    

    public function checkCoupon(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'coupon'  => 'required|string|min:7|max:7'
      ]);

      if ($validator->fails()) {
        return response()->json([
            'status' => '422',
            'message' => 'Validation Failed',
            'errors' => $validator->errors(),
        ], 422);
      }

      $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->toDateTimeString();

      $coupon = Coupon::where('code', $request->coupon)
                      ->where('status', 1)
                      ->where('valid_from','<=',$today)
                      ->where('valid_to','>=',$today);

      if(!$coupon->exists()){
        return response()->json([
            'status' => '403',
            'message' => "Sorry this coupon is not valid or has already expired",
        ], 403);
      }

      // check if already used
      if(Order::where('customer_id',Auth::id())->where('coupon',$request->coupon)->exists()){
         return response()->json([
            'status' => '403',
            'message' => "You've already used this coupon",
        ], 403);
      }
      $coupon = $coupon->first();
      $discount = '';
      if($coupon->type==1)
        $discount = $coupon->discount.'%';
      elseif($coupon->type==2)
        $discount = config('settings.currency').' '.$coupon->discount;
      return response()->json([
            'status'      =>  '200',
            'message'     =>  'Coupon Verified',
            'code'        =>  $coupon->code,
            'discount'    =>  $discount,
            'valid_from'  =>  $coupon->valid_from,
            'valid_to'    =>  $coupon->valid_to
        ], 200);
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
            'type'           => 'required|numeric',
            'pick_location'  => ['required','numeric',
                Rule::exists('user_addresses','id')
                    ->where(function ($query) use ($request) {
                      return $query->where('id', $request->pick_location)
                                   ->where('user_id', Auth::id());
                    })
            ],
            'pick_date'      => 'required|date',
            'pick_timerange' => 'required',
            'drop_location'  => 'required|numeric',
            'drop_date'      => 'nullable|date',
            'payment_type'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if($request->coupon){
            $validator = Validator::make($request->all(), [
                'coupon' => 'string|min:7|max:7'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => '422',
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $today = \Carbon\Carbon::now()->timezone(config('settings.timezone'))->toDateTimeString();

            $coupon = Coupon::where('code', $request->coupon)
                          ->where('status', 1)
                          ->where('valid_from','<=',$today)
                          ->where('valid_to','>=',$today);

            if(!$coupon->exists()){
                return response()->json([
                    'status' => '403',
                    'message' => "Sorry this coupon is not valid or has already expired",
                ], 403);
            }

          // check if already used
          if(Order::where('customer_id',Auth::id())->where('coupon',$request->coupon)->exists()){
             return response()->json([
                'status' => '403',
                'message' => "You've already used this coupon",
            ], 403);
          }
        }

        $input = [];
        if($request->type==1){
            $input = $request->only('type','pick_location','pick_date','pick_timerange','drop_location','coupon');
        }
        elseif($request->type==2){
            $input = $request->only('type','pick_location','pick_date','pick_timerange','drop_location','drop_date','drop_timerange','coupon');
            $input['urgent_charge'] = AppDefault::firstOrFail()->urgent_charge;
        }

        $input['customer_id'] = Auth::id();
        
        $order = Order::create($input);
        
        if($order){
            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order->id],
                ['payment_type' => $request->payment_type]
            );

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
        $orderDetails = Order::findOrFail($id);
        // if($orderDetails->customer_id!=Auth::id() && $orderDetails->driver_id!=Auth::id() && $orderDetails->drop_driver_id!=Auth::id()){
        // if($orderDetails->customer_id!=Auth::id()){
        //     return response()->json([
        //         'status'=>'403',
        //         'message'=>'You donot have access for this order'
        //     ],403);
        // }
        $orderDetails = Order::where('id',$id)
                             ->with('customer:id,fname,lname,phone',
                              'pick_location_details:id,name',
                              'drop_location_details:id,name',
                              'details:order_id,DAO,DTC')
                             ->first();
        return $orderDetails;
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

    public function customerOrderInvoice($order_id)
    {
        $order = Order::findOrFail($order_id);

        if($order->customer_id!=Auth::id()){
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
                                'status' => 3
                            ]);
            $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order_id],
                ['PFC'      => date(config('settings.dateTime'))]
            );
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

    public function cancelOrderForCustomer($order_id)
    {
        User::notifyOrderCancelled($order_id);
        
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
        
        // Notify Customer
        

        return response()->json([
            'status' => '200',
            'message' => 'Order has been cancelled and deleted',
        ], 200);
    }
}
