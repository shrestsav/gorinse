<?php

return [
    'date_format' => 'Y-m-d',
    'time_format' => 'g:i A',
    'dateTime'    => 'Y-m-d h:i:s',
    'OTP_expiry'  => 5,
    'VAT'  => 5,
    'delivery_charge'  => 105,
    // 'orderStatus' => [
    //     'pending' => 'Pending',
    //     'assigned' => 'Assigned',
    //     'processing' => 'Processing',
    //     'done' => 'Done',
    //     'returned' => 'Returned',
    // ],
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