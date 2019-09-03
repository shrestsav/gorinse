<?php

use App\AppDefault;
use App\Category;
use App\MainArea;
use App\Permission;
use App\Role;
use App\Service;
use App\User;
use App\UserDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        // Create Super Admin Role and Super Admin User
        $role = [
            'name'         => 'superAdmin', 
            'display_name' => 'Super Admin', 
            'description'  => 'Super Admin Role'
        ];
        $role = Role::create($role);
        $permission = Permission::get();
        foreach ($permission as $key => $value) {
            $role->attachPermission($value);
        }
        $user = [
            'fname'    => 'Super', 
            'lname'    => 'Admin', 
            'email'    => 'superadmin@admin.com', 
            'password' => Hash::make('admin12345')
        ];
        $user = User::create($user);
        $user->attachRole($role);

        //Add Customer and Driver Roles
        $other_roles = [
            [
                'name' => 'customer', 
                'display_name' => 'Customer', 
                'description' => 'Customer Level Role'
            ],
            [
                'name' => 'driver', 
                'display_name' => 'Driver', 
                'description' => 'Driver Level Role'
            ],
        ];
        
        foreach ($other_roles as $key => $value) {
            Role::create($value);
        }

        // Create Test Customer and Attach Role
        $testCustomer = [
            'fname' => 'Customer', 
            'lname' => 'Test', 
            'email' => 'customer@gorinse.com', 
            'phone' => '+971999999999'
        ];
        $testCustomer = User::create($testCustomer);
        $testCustomerDetails = UserDetail::create([
                                'user_id' => $testCustomer->id,
                                'referral_id' => 'YUD09EFG'
                            ]);
        $testCustomer->attachRole(Role::where('name','customer')->first()->id);
        
        // Create Test Driver and Attach Role
        $testDriver = [
            'fname' => 'Driver', 
            'lname' => 'Test', 
            'email' => 'driver@gorinse.com', 
            'phone' => '+971985112417',
        ];
        $testDriver = User::create($testDriver);
        $testDriverDetails = UserDetail::create([
                                'user_id' => $testDriver->id,
                                'area_id' => 1,
                                'referral_id' => 'TRD76EFG'
                            ]);
        $testDriver->attachRole(Role::where('name','driver')->first()->id);

        //Add Services
        $services = [
            [
                'name'        => 'Press Only', 
                'price'       => '40', 
                'description' => 'Clothes will be Pressed Only',
                'status'      => 1
            ],
            [
                'name'        => 'Clean & Press', 
                'price'       => '50', 
                'description' => 'Clothes will be cleaned and then pressed',
                'status'      => 1
            ],
            [
                'name'        => 'Wash & Press', 
                'price'       => '60', 
                'description' => 'Clothes will be washed and then pressed',
                'status'      => 1
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }        

        //Add Categories
        $categories = [
            [
                'name'        => 'Tops', 
                'icon'       => 'ico', 
                'description' => 'Top clothings like T-Shirt, Shirt, Blouse, Tops and what else',
                'status'      => 1
            ],
            [
                'name'        => 'Bottoms', 
                'icon'       => 'ico', 
                'description' => 'Bottom clothings like Pant, Skirt, Half Pant',
                'status'      => 1
            ],
            [
                'name'        => 'Undergarments', 
                'icon'       => 'ico', 
                'description' => '',
                'status'      => 1
            ],
            [
                'name'        => 'Coats', 
                'icon'       => 'ico', 
                'description' => '',
                'status'      => 1
            ],
            [
                'name'        => 'Miscellaneous', 
                'icon'       => 'ico', 
                'description' => 'Clothes will be washed and then pressed',
                'status'      => 1
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        } 

        //Add Main Areas
        $areas = [
            ['name' => 'Kingdom of Bahrain'],
            ['name' => 'Kingdom of Saudi Arabia'],
            ['name' => 'Kuwait'],
            ['name' => 'Qatar'],
            ['name' => 'Sultanate of Oman'],
            ['name' => 'United Arab Emirates'],
        ];

        foreach ($areas as $area) {
            MainArea::create($area);
        }

        $order_time = [
            "00:00 - 01:00", 
            "01:00 - 02:00", 
            "02:00 - 03:00", 
            "03:00 - 04:00", 
            "04:00 - 05:00", 
            "05:00 - 06:00", 
            "06:00 - 07:00", 
            "07:00 - 08:00", 
            "08:00 - 09:00", 
            "09:00 - 10:00", 
            "10:00 - 11:00", 
            "11:00 - 12:00", 
            "12:00 - 13:00", 
            "13:00 - 14:00", 
            "14:00 - 15:00", 
            "15:00 - 16:00", 
            "16:00 - 17:00", 
            "17:00 - 18:00", 
            "18:00 - 19:00", 
            "19:00 - 20:00", 
            "20:00 - 21:00", 
            "21:00 - 22:00", 
            "22:00 - 23:00", 
            "23:00 - 00:00"
        ];
        $driver_notes = [
            'Hole in pant',
            'Button is missing',
            'Very Rough pant'
        ];
        $online_chat = [
            'time'  => '9am - 11pm',
            'url'   => 'https://www.online_chat.com',
        ];

        $appDefaults = [
            'VAT'             =>  5,
            'delivery_charge' =>  105,
            'OTP_expiry'      =>  5,
            'order_time'      =>  json_encode($order_time),
            'driver_notes'    =>  json_encode($driver_notes),
            'FAQ_link'        =>  'https://www.faq.com',
            'online_chat'     =>  json_encode($online_chat),
            'hotline_contact' =>  '+97123456780',
            'company_email'   =>  '+97123456780',
            'company_logo'    =>  'company_logo.png',
            'app_rows'        =>  8,
            'sys_rows'        =>  8
        ];

        AppDefault::create($appDefaults);
    }
}
