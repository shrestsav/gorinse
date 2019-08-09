<?php

return [
    'date_format' => 'Y-m-d',
    'time_format' => 'g:i A',
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
        ],
        'On Hold' => [
        	'payment_pending' => 'Payment Pending',
        	'delivered' => 'Delivered',
        ],
        'Completed' => [
        	'paid' => 'Paid',
        ]
    ],

];