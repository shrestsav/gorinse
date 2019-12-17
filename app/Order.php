<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
{
    protected $hidden = ['assigned_status','order_invoice','total_amount'];
    protected $appends = ['assigned_status','order_invoice','total_amount'];
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
        'urgent_charge',
		'coupon',
    ];

    protected $casts = [
        'status' => 'int'
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

    /**
     * Get the order Invoice flag for users.
     *
     * @return status
     */
    public function getOrderInvoiceAttribute()
    {
        $orderInvoice = [];
        if($this->status>2){
            $orderInvoice = $this->generateInvoice();
        }
        return $orderInvoice;
    }

    /**
     * Get the Total Sales Amount.
     *
     * @return status
     */
    public function getTotalAmountAttribute()
    {
        $totalAmount = null;

        if($this->status>=7){
            $totalAmount = $this->totalSales();
        }

        return $totalAmount;
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

    public function generateInvoice()
    {
        $orderDetails = Order::where('id',$this->id)->with('orderItems.service','orderItems.item','customer','details')->firstOrFail();
        $totalAmount = 0;
        $totalQuantity = 0;
        $invoiceArr = [];
        $serviceName = '';
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            $rate = $item['service_charge']+$item['item_charge'];
            $amount = $rate*$itemQuantity;
            $totalQuantity += $itemQuantity;
            $totalAmount += $amount;

            $invoice = [
                'item_id'        => $item['item']['id'],
                'item'           => $item['item']['name'],
                'quantity'       => $itemQuantity,
                'service_charge' => $item['service_charge'],
                'item_charge'    => $item['item_charge'],
                'remarks'        => $item['remarks'],
                'price'          => $rate,
                'total'          => $amount,
            ];

            //Risky Business,,,, order should have only one service id for this to work properly
            $serviceName = $item['service']['name'];

            array_push($invoiceArr,$invoice);
        };
        $couponDiscount = 0;
        $couponDiscountAmount = 0;
        if($orderDetails->coupon){
            $couponDetails = Coupon::where('code',$orderDetails->coupon)->first();
            if($couponDetails){
                if($couponDetails->type==1){
                    $couponDiscount = $couponDetails->discount.'%';
                    $couponDiscountAmount = ($couponDetails->discount/100)*$totalAmount;
                    $totalAmount = $totalAmount - $couponDiscountAmount;
                }
                elseif($couponDetails->type==2){
                    $couponDiscount = $couponDetails->discount;
                    $couponDiscountAmount = $couponDetails->discount;
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
        
        //Calculate Estimated Delivery Time
        $est_delivery = null;
        if(($orderDetails->type==2 && $orderDetails->drop_date) || ($orderDetails->type==1 && $orderDetails->drop_date)){
            $dropDate = \Carbon\Carbon::parse($orderDetails->drop_date);
            $est_delivery = $dropDate->diffForHumans();
        }
        else if($orderDetails->type==1 && $orderDetails->drop_date==null){
            $EDT = AppDefault::firstOrFail()->EDT;
            $orderedDate = $orderDetails->created_at;
            $estimatedTimeFromOrderedDate = $orderedDate->addDays($EDT);
            $est_delivery = $estimatedTimeFromOrderedDate->diffForHumans();
        }

        $invoice = [
            "name"            => $orderDetails->customer->fname.' '.$orderDetails->customer->lname,
            "service"         => $serviceName,
            'order_type'      => config('settings.orderType')[$orderDetails->type],
            'order_status'    => $orderDetails->status,
            "total_quantity"  => $totalQuantity,
            "coupon_discount" => $couponDiscount,
            "total_amount"    => $totalAmount+$couponDiscountAmount,
            "VAT_percent"     => $vatPercent,
            "VAT"             => $VAT,
            "delivery_charge" => $deliveryCharge,
            "grand_total"     => $grandTotal,
            "PDR"             => $orderDetails->details->PDR,
            "est_delivery"    => $est_delivery
        ];

        $collection = collect([
            "items_details" => $collection,
            "invoice_details" => $invoice,
            // 'order_status' => config('settings.orderStatuses')
        ]);

        return $collection;
    }
    
    public function totalSales()
    {
        $orderDetails = Order::where('id',$this->id)->with('orderItems.service','orderItems.item','customer','details')->firstOrFail();
        $totalAmount = 0;
        $totalQuantity = 0;
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            $rate = $item['service_charge']+$item['item_charge'];
            $amount = $rate*$itemQuantity;
            $totalAmount += $amount;
        };
        $couponDiscount = 0;
        $couponDiscountAmount = 0;
        if($orderDetails->coupon){
            $couponDetails = Coupon::where('code',$orderDetails->coupon)->first();
            if($couponDetails){
                if($couponDetails->type==1){
                    $couponDiscount = $couponDetails->discount.'%';
                    $couponDiscountAmount = ($couponDetails->discount/100)*$totalAmount;
                    $totalAmount = $totalAmount - $couponDiscountAmount;
                }
                elseif($couponDetails->type==2){
                    $couponDiscount = $couponDetails->discount;
                    $couponDiscountAmount = $couponDetails->discount;
                    $totalAmount = $totalAmount - $couponDiscount;
                }
            }
        }
        $vatPercent = $orderDetails->VAT;
        $VAT = ($vatPercent/100)*$totalAmount;
        $deliveryCharge = $orderDetails->delivery_charge;
        $grandTotal = $totalAmount+$VAT+$deliveryCharge;
        
        return $grandTotal;
    }

    //Works for single Service Id
    public function generateInvoiceForUser()
    {
        $orderDetails = Order::where('id',$this->id)->with('orderItems.service','orderItems.item','customer','details','pickDriver:id,fname,lname,phone','dropDriver:id,fname,lname,phone')->firstOrFail();
        $totalAmount = 0;
        $totalQuantity = 0;
        $invoiceArr = [];
        $serviceName = '';
        foreach ($orderDetails->orderItems as $item) {
            $itemQuantity = $item['quantity'];
            $rate = $item['service_charge']+$item['item_charge'];
            $amount = $rate*$itemQuantity;
            $totalQuantity += $itemQuantity;
            $totalAmount += $amount;

            $invoice = [
                'item_id'   => $item['item']['id'],
                'item'      => $item['item']['name'],
                'quantity'  => $itemQuantity,
                'remarks'   => $item['remarks'],
                'price'     => $rate,
                'total'     => $amount,
            ];

            //Risky Business,,,, order should have only one service id for this to work properly
            $serviceName = $item['service']['name'];

            array_push($invoiceArr,$invoice);
        };

        //Add Additional Urgent Charge for Urgent Order Types
        $urgentCharge = $orderDetails->urgent_charge;
        $totalAmount = $totalAmount + $urgentCharge;

        $couponDiscount = 0;
        $couponDiscountAmount = 0;
        if($orderDetails->coupon){
            $couponDetails = Coupon::where('code',$orderDetails->coupon)->first();
            if($couponDetails){
                if($couponDetails->type==1){
                    $couponDiscount = $couponDetails->discount.'%';
                    $couponDiscountAmount = ($couponDetails->discount/100)*$totalAmount;
                    $totalAmount = $totalAmount - $couponDiscountAmount;
                }
                elseif($couponDetails->type==2){
                    $couponDiscount = $couponDetails->discount;
                    $couponDiscountAmount = $couponDetails->discount;
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
        
        //Calculate Estimated Delivery Time
        $est_delivery = null;
        if(($orderDetails->type==2 && $orderDetails->drop_date) || ($orderDetails->type==1 && $orderDetails->drop_date)){
            $dropDate = \Carbon\Carbon::parse($orderDetails->drop_date);
            // $est_delivery = $dropDate->diffForHumans();
            $est_delivery = $dropDate->format('Y-m-d');
        }
        else if($orderDetails->type==1 && $orderDetails->drop_date==null){
            $EDT = AppDefault::firstOrFail()->EDT;
            $orderedDate = $orderDetails->created_at;
            $estimatedTimeFromOrderedDate = $orderedDate->addDays($EDT);
            // $est_delivery = $estimatedTimeFromOrderedDate->diffForHumans();
            $est_delivery = $estimatedTimeFromOrderedDate->format('Y-m-d');
        }

        //Payments
        if($orderDetails->details->payment_type=='1')
            $payment_type = 'Cash on Delivery';
        elseif($orderDetails->details->payment_type=='3')
            $payment_type = 'Paypal';
        else
            $payment_type = 'Card';
        
        $payment_status = false;
        if($orderDetails->payment)
            $payment_status = true;

        $invoice = [
            "name"            => $orderDetails->customer->fname.' '.$orderDetails->customer->lname,
            "service"         => $serviceName,
            'order_type'      => config('settings.orderType')[$orderDetails->type],
            'order_status'    => $orderDetails->status,
            "total_quantity"  => $totalQuantity,
            "urgent_charge"   => $urgentCharge,
            "coupon_discount" => $couponDiscount,
            "total_amount"    => $totalAmount+$couponDiscountAmount-$urgentCharge,
            "VAT_percent"     => $vatPercent,
            "VAT"             => $VAT,
            "delivery_charge" => $deliveryCharge,
            "grand_total"     => $grandTotal,
            "PDR"             => $orderDetails->details->PDR,
            "payment_type"    => $payment_type,
            "payment_status"  => $payment_status,
            "OT"              => $orderDetails->created_at,
            "DAO"             => $orderDetails->details->DAO,
            "DTC"             => $orderDetails->details->DTC,
            "PT"              => $orderDetails->details->PT,
            "est_delivery"    => $est_delivery,
            "pick_driver"     => $orderDetails->pickDriver,  
            "drop_driver"     => $orderDetails->dropDriver
        ];

        $collection = collect([
            "items_details" => $collection,
            "invoice_details" => $invoice,
            // 'order_status' => config('settings.orderStatuses')
        ]);

        return $collection;
    }
}
