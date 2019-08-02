<?php

return [
  'createUser' => [
    'User Information' => [
      'fname' => [
        'display_name' => 'First Name',
        'col' => '6',
        'type' =>  'text',
      ],
      'lname' => [
        'display_name' => 'Last Name',
        'col' => '6',
        'type' =>  'text',
      ],
      'username' => [
        'display_name' => 'Username',
        'col' => '6',
        'type' =>  'text',
      ],
      'email' => [
        'display_name' => 'Email Address',
        'col' => '6',
        'type' =>  'email',
      ],
      'd_o_b' => [
        'display_name' => 'Date of Birth',
        'col' => '4',
        'type' =>  'date',
      ],
    ],
    'Contact Information' => [
      'address' => [
        'display_name' => 'Address',
        'col' => '4',
        'type' =>  'text',
      ],
      'contact' => [
        'display_name' => 'Contact No',
        'col' => '4',
        'type' =>  'text',
      ],
      'joined_date' => [
        'display_name' => 'Joined Date',
        'col' => '4',
        'type' =>  'date',
      ],
    ],
    'About' => [
      'about' => [
        'display_name' => 'About',
        'col' => '12',
        'type' =>  'textarea',
      ],
    ],
  ],
  'createOrder' => [
    'Order Information' => [
      'customer' => [
        'display_name' => 'Select Customer',
        'col' => '4',
        'type' =>  'select',
      ],
      'order_date' => [
        'display_name' => 'Order Date',
        'col' => '4',
        'type' =>  'date',
      ],
      'order_type' => [
        'display_name' => 'Type',
        'col' => '4',
        'type' =>  'select',
      ],
      'pickup_location' => [
        'display_name' => 'Pickup Location',
        'col' => '4',
        'type' =>  'text',
      ],
      'pickup_datetime' => [
        'display_name' => 'Pickup Date & Time',
        'col' => '4',
        'type' =>  'datetime',
      ],
      'drop_location' => [
        'display_name' => 'Drop Location',
        'col' => '4',
        'type' =>  'text',
      ],
      'drop_datetime' => [
        'display_name' => 'Drop Date & Time',
        'col' => '4',
        'type' =>  'datetime',
      ],
      'price' => [
        'display_name' => 'Price',
        'col' => '4',
        'type' =>  'number',
      ],
      'vat_amount' => [
        'display_name' => 'VAT',
        'col' => '4',
        'type' =>  'number',
      ],
      'delivery_charge' => [
        'display_name' => 'Delivery Charge',
        'col' => '4',
        'type' =>  'number',
      ],
      'status' => [
        'display_name' => 'Status',
        'col' => '4',
        'type' =>  'select',
      ],
    ],
  ],
];
