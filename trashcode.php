
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
     * Add Items to Orders supports multiple service ids.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function orderItems(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'order_id' => 'required|numeric',
    //         'service_id' => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => '422',
    //             'message' => 'Validation Failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $appDefault = AppDefault::firstOrFail();
    //     $VAT = $appDefault->VAT;
    //     $deliveryCharge = $appDefault->delivery_charge;

    //     $order_id = $request->order_id;
    //     $service_id = $request->service_id;
    //     $serviceCharge = Service::findOrFail($service_id)->price;
    //     $order = Order::where('id',$order_id);
    //     if($order->exists()){
    //         if(Auth::id()==$order->first()->driver_id){
    //             foreach($request->items as $item){
    //                 $itemCharge = Item::findOrFail($item['item_id'])->price;
    //                 $orderItem = new OrderItem;
    //                 $orderItem->order_id = $order_id;
    //                 $orderItem->service_id = $service_id;
    //                 $orderItem->item_id = $item['item_id'];
    //                 $orderItem->quantity = $item['quantity'];
    //                 $orderItem->service_charge = $serviceCharge;
    //                 $orderItem->item_charge = $itemCharge;
    //                 $orderItem->remarks = $item['remarks'];
    //                 $orderItem->save();
    //             }
    //             $order->update([
    //                 'VAT' => $VAT,
    //                 'delivery_charge' => $deliveryCharge
    //             ]);
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

    
    // //Works for single Service Id
    // public function genInvoice($order_id)
    // {
    //     $orderDetails = Order::where('id',$order_id)->with('orderItems.service','orderItems.item','customer','details')->first();
    //     $totalAmount = 0;
    //     $totalQuantity = 0;
    //     $invoiceArr = [];
    //     $serviceName = '';
    //     foreach ($orderDetails->orderItems as $item) {
    //         $itemQuantity = $item['quantity'];
    //         // $serviceCharge = $item['service']['price'];
    //         // $itemCharge = $item['item']['price'];
    //         $rate = $item['service_charge']+$item['item_charge'];
    //         $amount = $rate*$itemQuantity;
    //         $totalQuantity += $itemQuantity;
    //         $totalAmount += $amount;

    //         $invoice = [
    //             'item' => $item['item']['name'],
    //             'quantity' => $itemQuantity,
    //             'total' => $amount,
    //         ];

    //         //Risky Business,,,, order should have only one service id for this to work properly
    //         $serviceName = $item['service']['name'];

    //         array_push($invoiceArr,$invoice);
    //     };
    //     $collection = collect($invoiceArr);
    //     $grouped_collection = $collection->groupBy(['service'])->toArray();       
    //     $vatPercent = $orderDetails->VAT;
    //     $VAT = ($vatPercent/100)*$totalAmount;
    //     $deliveryCharge = $orderDetails->delivery_charge;
    //     $grandTotal = $totalAmount+$VAT+$deliveryCharge;
        
    //     $invoice = [
    //         "name"            => $orderDetails->customer->fname.' '.$orderDetails->customer->lname,
    //         "service"         => $serviceName,
    //         'order_type'      => config('settings.orderType')[$orderDetails->type],
    //         "total_quantity"  => $totalQuantity,
    //         "total_amount"    => $totalAmount,
    //         "VAT_percent"     => $vatPercent,
    //         "VAT"             => $VAT,
    //         "delivery_charge" => $deliveryCharge,
    //         "grand_total"     => $grandTotal,
    //         "PDR"             => $orderDetails->details->PDR
    //     ];
    //     $invoiceCollection = [
    //         "items_details" => $collection,
    //         "invoice_details" => $invoice,
            
    //     ];

    //     return $invoiceCollection;
    // }