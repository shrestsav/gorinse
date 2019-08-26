export const fields = {
  'createUser' : {
    'User Information' : {
      'fname' : {
        'display_name' : 'First Name',
        'col' : '6',
        'type' :  'text',
      },
      'lname' : {
        'display_name' : 'Last Name',
        'col' : '6',
        'type' :  'text',
      },
      'area_id' : {
        'display_name' : 'Select Driver Area',
        'col' : '4',
        'type' :  'select',
      },
      'phone' : {
        'display_name' : 'Phone',
        'col' : '4',
        'type' :  'text',
      },
      'username' : {
        'display_name' : 'Username',
        'col' : '4',
        'type' :  'text',
      },
      'email' : {
        'display_name' : 'Email Address',
        'col' : '4',
        'type' :  'email',
      },
    },
    'Other Information' : {
      'dob' : {
        'display_name' : 'Date of Birth',
        'col' : '4',
        'type' :  'date',
      },
      'joined_date' : {
        'display_name' : 'Joined Date',
        'col' : '4',
        'type' :  'date',
      },
      'description' : {
        'display_name' : 'About',
        'col' : '12',
        'type' :  'textarea',
      },
    },
  },
  'createOrder' : {
    'Order Information' : {
      'customer_id' : {
        'display_name' : 'Select Customer',
        'col' : '4',
        'type' :  'select',
      },
      'type' : {
        'display_name' : 'Type',
        'col' : '4',
        'type' :  'select',
      },
      'pick_location' : {
        'display_name' : 'Pickup Location',
        'col' : '4',
        'type' :  'number',
      },
      'pick_datetime' : {
        'display_name' : 'Pickup Date & Time',
        'col' : '4',
        'type' :  'datetime',
      },
      'drop_location' : {
        'display_name' : 'Drop Location',
        'col' : '4',
        'type' :  'number',
      },
      'drop_datetime' : {
        'display_name' : 'Drop Date & Time',
        'col' : '4',
        'type' :  'datetime',
      },
      'price' : {
        'display_name' : 'Price',
        'col' : '4',
        'type' :  'number',
      },
      'vat_amount' : {
        'display_name' : 'VAT',
        'col' : '4',
        'type' :  'number',
      },
      'delivery_charge' : {
        'display_name' : 'Delivery Charge',
        'col' : '4',
        'type' :  'number',
      },
    },
  },
  'createService' : {
    'Information' : {
      'name' : {
        'display_name' : 'Service Name',
        'col' : '6',
        'type' :  'text',
      },
      'price' : {
        'display_name' : 'Service Price',
        'col' : '6',
        'type' :  'number',
      },
      'description' : {
        'display_name' : 'Description',
        'col' : '12',
        'type' :  'textarea',
        'placeholder' : 'Write Brief Description',
      },
    },
  },
  'createCategory' : {
    'Information' : {
      'name' : {
        'display_name' : 'Category Name',
        'col' : '12',
        'type' :  'text',
      },
      'description' : {
        'display_name' : 'Description',
        'col' : '12',
        'type' :  'textarea',
        'placeholder' : 'Write Brief Description',
      },
    },
  },
  'createItem' : {
    'Information' : {
      'category_id' : {
        'display_name' : 'Choose Category',
        'col' : '3',
        'type' :  'select',
      },
      'name' : {
        'display_name' : 'Item Name',
        'col' : '3',
        'type' :  'text',
      },
      'price' : {
        'display_name' : 'Price',
        'col' : '3',
        'type' :  'number',
      },
      'icon' : {
        'display_name' : 'Icon Code',
        'col' : '3',
        'type' :  'text',
      },
      'description' : {
        'display_name' : 'Description',
        'col' : '12',
        'type' :  'textarea',
        'placeholder' : 'Write Brief Description',
      },
    },
  }
}

