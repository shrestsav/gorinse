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
        'type' :  'select',
      },
      'pick_date' : {
        'display_name' : 'Pickup Date',
        'col' : '4',
        'type' :  'date',
      },
      'pick_timerange' : {
        'display_name' : 'Pickup Timerange',
        'col' : '4',
        'type' :  'select',
      },
      'drop_location' : {
        'display_name' : 'Drop Location',
        'col' : '4',
        'type' :  'select',
      },
      'drop_date' : {
        'display_name' : 'Drop Date',
        'col' : '4',
        'type' :  'date',
      },
      'drop_timerange' : {
        'display_name' : 'Drop Timerange',
        'col' : '4',
        'type' :  'select',
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
        'col' : '6',
        'type' :  'text',
      },
      'icon' : {
        'display_name' : 'Icon Code',
        'col' : '6',
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
        'col' : '4',
        'type' :  'select',
      },
      'name' : {
        'display_name' : 'Item Name',
        'col' : '4',
        'type' :  'text',
      },
      'price' : {
        'display_name' : 'Price',
        'col' : '4',
        'type' :  'number',
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

