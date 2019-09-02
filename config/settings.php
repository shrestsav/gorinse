<?php

return [
    'timezone'    => 'Asia/Dubai',
    'date_format' => 'Y-m-d',
    'time_format' => 'g:i A',
    'dateTime'    => 'Y-m-d h:i:s',
    'rows'        => 10,
    'OTP_expiry'  => 5,
    'VAT'         => 5,
    'delivery_charge'  => 105,
    'orderStatuses' => [
        0 => 'Pending',
        1 => 'Assigned',
        // Drived added items to invoice
        2 => 'Invoice Generated',
        3 => 'Confirmed by Customer',
        4 => 'On Work',
        5 => 'Assigned for Delivery',
        //New
        6 => 'Picked for Delivery',

        7 => 'Delivered',
        //New
        8 => 'Delivery Received by Customer', 
        9 => 'Paid'
    ],
    'orderStatus' => [
        'Pending' => [
            'pending' => 'Pending',
            'assigned' => 'Assigned',
            'invoice_generated' => 'Invoice Generated',
            'customer_confirmed' => 'Confirmed by Customer',
        ],
        'Received' => [
            'on_work' => 'On Work',
        ],
        'Ready for Delivery' => [
            'assigned_delivery' => 'Assigned for Delivery',
            //Picked status pani halney
        ],
        'On Hold' => [
            // 'payment_pending' => 'Payment Pending',
            'delivered' => 'Delivered',
        ],
        'Completed' => [
            'paid' => 'Paid',
        ]
    ],
    'addressType' => [
        1 => 'Apartment',
        2 => 'Villa',
        3 => 'Hotel',
        4 => 'Office'
    ],
    'mainArea' => [
        1 => 'Dubai',
        2 => 'AbuDabi',
        3 => 'UAE',
        4 => 'Kathmandu'
    ],
    'orderType' => [
    	1 => 'Normal',
        2 => 'Urgent'
    ],

];