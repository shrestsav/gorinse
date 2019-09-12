<?php

namespace App\Traits;

use App\Order;
use App\User;

trait NotificationLogics
{
        
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
                       })->pluck('id')->toArray();

        // $customer_id = $order->customer_id;

        $notification = [
            'notifyType' => 'new_order',
            'message' => $order->customer->fname. ' just created a new order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }

        // Send Notification to All Drivers of that particular area
        foreach($driver_ids as $id){
            User::find($id)->pushNotification($notification);
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
                       })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notification = [
            'notifyType' => 'order_accepted',
            'message' => $order->pickDriver->fname. ' just accepted order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        // Send Order Accepted Notification to Customer
            User::find($customer_id)->pushNotification($notification);
        
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
                       })->pluck('id')->toArray();

        $notification = [
            'notifyType' => 'pickup_cancelled',
            'message' => $order->pickDriver->fname. ' has cancelled pickup for order #'.$order->id,
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
                       })->pluck('id')->toArray();

        $customer_id = $order->customer_id;
        $driver_id = $order->driver_id;

        $notifyCustomer = [
            'notifyType' => 'order_accepted',
            'message' => $order->pickDriver->fname. ' just accepted order #'.$order->id,
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
        User::find($customer_id)->pushNotification($notifyCustomer);

        // Send Order Assigned Notification to Driver
        User::find($driver_id)->pushNotification($notifyDriver);
        
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
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'invoice_generated',
            'message' => $order->pickDriver->fname. ' has generated an invoice for order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
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
        User::find($customer_id)->pushNotification($notificationCustomer);
        
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
                               })->pluck('id')->toArray();

        $driver_id = $order->driver_id;

        $notification = [
            'notifyType' => 'invoice_confirmed',
            'message' => $order->customer->fname. ' has confirmed invoice for order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notification);
        }
        // Send Order Accepted Notification to Customer
        User::find($driver_id)->pushNotification($notification);
        
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
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'dropped_at_office',
            'message' => $order->pickDriver->fname. ' has dropped clothes at office for order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
            'notifyType' => 'dropped_at_office',
            'message' => 'Your clothes for order has been sent for dry washing',
            'model' => 'order',
            'url' => $order->id
        ];

        // Send Order Accepted Notification to All Superadmins
        foreach($superAdmin_ids as $id){
            User::find($id)->pushNotification($notificationAdmin);
        }
        // Send Order Accepted Notification to Customer
        User::find($customer_id)->pushNotification($notificationCustomer);
        
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
                               })->pluck('id')->toArray();

        $driver_id = $order->drop_driver_id;

        $notificationDriver = [
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
        User::find($driver_id)->pushNotification($notificationDriver);
        
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
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'picked_from_office',
            'message' => $order->dropDriver->fname. ' has picked clothes from office for delivery for order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
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
        User::find($customer_id)->pushNotification($notificationCustomer);
        
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
                               })->pluck('id')->toArray();

        $customer_id = $order->customer_id;

        $notificationAdmin = [
            'notifyType' => 'delivered_to_customer',
            'message' => $order->dropDriver->fname.' has delivered clothes to customer '.$order->customer->fname.' for order #'.$order->id,
            'model' => 'order',
            'url' => $order->id
        ];

        $notificationCustomer = [
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
        User::find($customer_id)->pushNotification($notificationCustomer);
        
        return true;
    }
}
