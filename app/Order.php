<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'driver_id',
		'type',
		'pick_location',
		'pick_date',
		'pick_timerange',
		'drop_location',
        'drop_date',
        'drop_timerange',
        'payment',
		'status'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
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

    public static function generateInvoice($order_id)
    {
        $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item')->first();
        if(!$orderDetails->status>=2){
            return response()->json([
                'status' => 403,
                'message'=>'Invoice has not been generated for this order'
            ]);
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
}
