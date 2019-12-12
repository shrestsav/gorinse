<?php

namespace App\Traits;

use App\DeviceToken;
use App\Mail\notifyMail;
use App\Order;
use App\User;
use Mail;

// use LaravelFCM\Message\OptionsBuilder;
// use LaravelFCM\Message\PayloadDataBuilder;
// use LaravelFCM\Message\PayloadNotificationBuilder;
// use FCM;


trait NotificationLogics
{
    // public function sendFCMNotification($notification)
    // {  
    //     $device_tokens = DeviceToken::where('user_id',$this->id)->pluck('device_token')->toArray();
    //     if(count($device_tokens)){
    //         $optionBuilder = new OptionsBuilder();
    //         $optionBuilder->setTimeToLive(60*20);

    //         $title = implode(' ', array_map('ucfirst', explode('_', $notification['notifyType'])));
    //         $notificationBuilder = new PayloadNotificationBuilder($title);
    //         $notificationBuilder->setBody($notification['message'])
    //                             ->setSound('default');

    //         $dataBuilder = new PayloadDataBuilder();
    //         $dataBuilder->addData([
    //             'a_data' => 'test data'
    //         ]);

    //         $option = $optionBuilder->build();
    //         $notification = $notificationBuilder->build();
    //         $data = $dataBuilder->build();

    //         $downstreamResponse = FCM::sendTo($device_tokens, $option, $notification, $data);

    //         $expiredTokens = $downstreamResponse->tokensToDelete();

    //         if(count($expiredTokens)){
    //             DeviceToken::whereIn('device_token',$expiredTokens)->delete();
    //         } 
    //     }
    // }

    /**
    * Send Welcome Email to Customer
    */
    public static function notifyNewRegistration($customer_id)
    {  
        $customer = User::find($customer_id);
        
        $customerMailData = [
            'emailType' => 'new_registration',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'subject'   => "GO-RINSE: Welcome ".$customer->full_name,
            'message'   => "Welcome to GO-RINSE..",
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));
        
        return true;
    }
        
    /**
    * Notify Admins and Drivers of that specific area
    */
    public static function notifyNewOrder($order_id)
    {  
        $order = Order::find($order_id);
        $area_id = $order->pick_location_details->area_id;
        $driver_ids = User::whereHas('roles', function ($query) {
                                $query->where('name', '=', 'driver');
                            })
                            ->join('user_details as ud','users.id','=','ud.user_id')
                            ->where('ud.area_id','=',$area_id)
                            ->pluck('users.id')
                            ->toArray();

        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                              ->pluck('id')
                              ->toArray();

        $notification = [
            'notifyType' => 'new_order',
            'message' => $order->customer->fname. ' placed a new order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $customer = User::find($order->customer_id);

        $customerMailData = [
            'emailType' => 'new_order',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'orderID'   => $order_id,
            'subject'   => "GO-RINSE: Your Order: #".$order_id. " has been placed",
            'message'   => "We've received your New Order: #".$order_id. ". We will contact you soon.",
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        // Send Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }

        // Send Notification to All Drivers of that particular area
        foreach($driver_ids as $driver_id){
            User::find($driver_id)->AppNotification($notification);
        }
        
        return true;
    }

    /**
    * Notify Admins and Customer of that specific order
    */
    public static function notifyAcceptOrder($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notifyCustomer = [
            'notifyType' => 'order_accepted',
            'message'    => 'Your Order #'.$order->id.' has been accepted by '. $order->pickDriver->fname. ' for pickup, please keep your items ready.',
            'model'      => 'order',
            'url'        => $order->id
        ];

        $notifyAdmin = [
            'notifyType' => 'order_accepted',
            'message' => 'Order #'.$order->id.' has been accepted by '. $order->pickDriver->fname. ' for pickup, please keep your items ready.',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notifyAdmin);
        }

        // Send Order Accepted Notification to Customer    
        User::find($customer_id)->AppNotification($notifyCustomer); 
        
        // Email Notification to Customer
        $customer = User::find($order->customer_id);
        $customerMailData = [
            'emailType' => 'order_accepted',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'orderID'   => $order_id,
            'subject'   => "GO-RINSE: Order: #".$order_id. " Accepted",
            'message'   => 'Your Order #'.$order->id.' has been accepted by '. $order->pickDriver->fname. ' for pickup, please keep your items ready.'
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        return true;
    }

    /**
    * Notify Admins
    */
    public static function notifyOrderCancelled($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $notification = [
            'notifyType' => 'order_cancelled',
            'message' => $order->customer->fname. ' has cancelled Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Cancel Order Mail to Admin
        $adminMailData = [
            'emailType' => 'order_cancelled',
            'name'      => 'GO-RINSE',
            'email'     => 'shrestsav@gmail.com',
            'subject'   => 'GO-RINSE: Order #'.$order->id.'Cancelled',
            'message'   => $order->customer->fname. ' has cancelled Order #'.$order->id,
        ];

        Mail::send(new notifyMail($adminMailData));

        // Send Cancel Order Mail to customer
        $customer = User::find($order->customer_id);
        
        $customerMailData = [
            'emailType' => 'order_cancelled',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'subject'   => "GO-RINSE: Order Cancelled",
            'message'   => "Your Order #".$order_id." has been cancelled",
        ];

        Mail::send(new notifyMail($customerMailData));

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        
        return true;
    }

    /**
    * Notify Admins
    */
    public static function notifyCancelForPickup($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $notification = [
            'notifyType' => 'pickup_cancelled',
            'message' => $order->pickDriver->fname. ' has cancelled pickup for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        
        return true;
    }

    /**
    * Notify Admins and Driver of that specific order
    */
    public static function notifyAssignedForPickup($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $customer_id = $order->customer_id;
        $driver_id = $order->driver_id;

        $notifyCustomer = [
            'notifyType' => 'order_accepted',
            'message' => 'Your Order #'.$order->id.' has been accepted by '. $order->pickDriver->fname. ' for pickup, please keep your items ready.',
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyDriver = [
            'notifyType' => 'assigned_for_pickup',
            'message' => 'Order #'.$order->id. ' has been assigned to you for pickup',
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyAdmin = [
            'notifyType' => 'assigned_for_pickup',
            'message' => 'Order #'.$order->id. ' has been assigned to '.$order->pickDriver->fname.' for pickup',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notifyAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->AppNotification($notifyCustomer);

        // Send Order Assigned Notification to Driver
        User::find($driver_id)->AppNotification($notifyDriver); 

        // Email Notification to Customer
        $customer = User::find($order->customer_id);
        $customerMailData = [
            'emailType' => 'order_accepted',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'orderID'   => $order_id,
            'subject'   => "GO-RINSE: Order: #".$order_id. " Accepted",
            'message'   => 'Your Order #'.$order->id.' has been accepted by '. $order->pickDriver->fname. ' for pickup, please keep your items ready.'
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        return true;
    }
    // assign order left

    /**
    * Notify Admins and Customer of that specific order
    */
    public static function notifyInvoiceGenerated($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'invoice_generated',
            'message' => $order->pickDriver->fname. ' has generated an invoice for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyCustomer = [
            'notifyType' => 'invoice_generated',
            'message' => 'An Invoice has been generated for your order, please check and confirm your order',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->AppNotification($notifyCustomer);
        return true;
    }

    /**
    * Notify Admins and Driver of that specific order
    */
    public static function notifyInvoiceConfirmed($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $driver_id = $order->driver_id;

        $notification = [
            'notifyType' => 'invoice_confirmed',
            'message' => $order->customer->fname. ' has confirmed invoice for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Invoice Confirmed Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }

        // Send Invoice Confirmed Notification to Pick Driver
        User::find($driver_id)->AppNotification($notification);

        // Email Notification to Customer
        $customer = User::find($order->customer_id);
        $customerMailData = [
            'emailType'     => 'invoice_confirmed',
            'name'          => $customer->full_name,
            'email'         => $customer->email,
            'orderID'       => $order_id,
            'subject'       => "GO-RINSE: Order: #".$order_id. " shipped for laundry",
            'message'       => "Your Order #".$order_id. " has been shipped for laundry. We will contact you soon",
            'orderDetails'  => $order->generateInvoiceForUser()
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        return true;
    }

    /**
    * Notify Admins and Customer of that specific order
    */
    public static function notifyDroppedAtOffice($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'dropped_at_office',
            'message' => $order->pickDriver->fname. ' has dropped clothes at office for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyCustomer = [
            'notifyType' => 'dropped_at_office',
            'message' => 'Your clothes for order has been sent for washing',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->AppNotification($notifyCustomer);
        
        return true;
    }
    
    /**
    * Notify Admins and Driver of that specific order
    */
    public static function notifyAssignedForDelivery($order_id)
    {  
        $order = Order::find($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $driver_id = $order->drop_driver_id;

        $notifyDriver = [
            'notifyType' => 'assigned_for_delivery',
            'message' => 'Order #'.$order->id.' has been assigned to you for delivery on '.$order->drop_date,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationAdmin = [
            'notifyType' => 'assigned_for_delivery',
            'message' => 'Order #'.$order->id.' has been assigned to '.$order->dropDriver->fname.' for delivery on '.$order->drop_date,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($driver_id)->AppNotification($notifyDriver);
        return true;
    }

    /**
    * Notify Admins and Customer of that specific order
    */
    public static function notifyPickedFromOffice($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::findOrFail($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'picked_from_office',
            'message' => $order->dropDriver->fname. ' has picked clothes from office for delivery for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyCustomer = [
            'notifyType' => 'picked_from_office',
            'message' => 'Your clothes for Order #'.$order->id.' is on process of delivery',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->AppNotification($notifyCustomer);

        return true;
    }

    /**
    * Notify Admins and Customer of that specific order
    */
    public static function notifyDroppedAtCustomer($order_id)
    {  
        //All Admins and Customer who ordered will get notified
        $order = Order::findOrFail($order_id);
        $superAdmin_ids = User::whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'superAdmin');
                                })
                                ->pluck('id')
                                ->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'delivered_to_customer',
            'message' => $order->dropDriver->fname.' has delivered clothes to customer '.$order->customer->fname.' for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notifyCustomer = [
            'notifyType' => 'delivered_to_customer',
            'message' => 'Clothes delivered for Order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->AppNotification($notifyCustomer);

        // Email Notification to Customer
        $customer = User::find($order->customer_id);
        $customerMailData = [
            'emailType' => 'delivered_to_customer',
            'name'      => $customer->full_name,
            'email'     => $customer->email,
            'orderID'   => $order_id,
            'subject'   => "GO-RINSE: Order: #".$order_id. " Delivered",
            'message'   => "Your Order #".$order_id. " has been delivered to you. If you have any queries contact our support team."
        ];
        
        // Notify Customer in email
        Mail::send(new notifyMail($customerMailData));

        return true;
    }
}
