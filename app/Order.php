<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
{
    protected $hidden = ['assigned_status'];
    protected $appends = ['assigned_status'];
    protected $fillable = [
        'customer_id',
        'driver_id',
        'drop_driver_id',
		'type',
		'pick_location',
		'pick_date',
		'pick_timerange',
		'drop_location',
        'drop_date',
        'drop_timerange',
        'payment',
        'status',
        'VAT',
        'delivery_charge',
		'coupon',
    ];

    /**
     * Get the assigned_status flag for users.
     *
     * @return status
     */
    public function getAssignedStatusAttribute()
    {
        $status = null;
        if($this->attributes['driver_id']==Auth::id() && $this->attributes['drop_driver_id']!=Auth::id())
        {
            $status = 1;
        }
        elseif($this->attributes['driver_id']!=Auth::id() && $this->attributes['drop_driver_id']==Auth::id())
        {
            $status = 2;
        }
        elseif($this->attributes['driver_id']==Auth::id() && $this->attributes['drop_driver_id']==Auth::id())
        {
            $status = 3;
        }
        return $status;
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    //Pahila yo use garthyo ailey pickDriver, hataihalna bhayena
    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function pickDriver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function dropDriver()
    {
        return $this->belongsTo(User::class,'drop_driver_id');
    }

    public function pick_location_details()
    {
        return $this->belongsTo(UserAddress::class,'pick_location');
    }

    public function drop_location_details()
    {
        return $this->belongsTo(UserAddress::class,'drop_location');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function details()
    {
        return $this->hasOne(OrderDetail::class);
    }

    public static function generateInvoice($order_id)
    {
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item')->first();
        if(!$orderDetails->status>=2){
            return null;
        }
        $totalAmount = 0;
        $totalQuantity = 0;
        $invoiceArr = [];
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            $serviceCharge = $item['service_charge'];
            $itemCharge = $item['item_charge'];
            $remarks = $item['remarks'];
            $rate = $serviceCharge+$itemCharge;
            $amount = $rate*$itemQuantity;
            $totalQuantity += $itemQuantity;
            $totalAmount += $amount;

            $invoice = [
                'service' => $item['service']['name'],
                'item' => $item['item']['name'],
                'quantity' => $itemQuantity,
                'service_charge' => $serviceCharge,
                'item_charge' => $itemCharge,
                'total' => $amount,
                'remarks' => $remarks,
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


    //Works for single Service Id
    public function generateInvoiceForUser()
    {
        $orderDetails = Order::where('id',$this->id)->with('orderItems.service','orderItems.item','customer','details')->firstOrFail();
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
                'item_id' => $item['item']['id'],
                'item' => $item['item']['name'],
                'quantity' => $itemQuantity,
                'price' => $rate,
                'total' => $amount,
            ];

            //Risky Business,,,, order should have only one service id for this to work properly
            $serviceName = $item['service']['name'];

            array_push($invoiceArr,$invoice);
        };
        $couponDiscount = 0;
        if($orderDetails->coupon){
            $couponDetails = Coupon::where('code',$orderDetails->coupon)->first();
            if($couponDetails){
                if($couponDetails->type==1){
                    $couponDiscount = $couponDetails->discount.'%';
                    $totalAmount = $totalAmount - ($couponDetails->discount/100)*$totalAmount;
                }
                elseif($couponDetails->type==2){
                    $couponDiscount = $couponDetails->discount;
                    $totalAmount = $totalAmount - $couponDiscount;
                }
            }
        }
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
            'order_status'    => $orderDetails->status,
            "total_quantity"  => $totalQuantity,
            "coupon_discount" => $couponDiscount,
            "total_amount"    => $totalAmount+$couponDiscount,
            "VAT_percent"     => $vatPercent,
            "VAT"             => $VAT,
            "delivery_charge" => $deliveryCharge,
            "grand_total"     => $grandTotal,
            "PDR"             => $orderDetails->details->PDR
        ];

        $collection = collect([
            "items_details" => $collection,
            "invoice_details" => $invoice,
            // 'order_status' => config('settings.orderStatuses')
        ]);

        return $collection;
    }
}
