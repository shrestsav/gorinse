<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\AppDefault;
use App\Permission;

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
        // Create Admin Role
        $role = ['name' => 'superAdmin', 'display_name' => 'Super Admin', 'description' => 'Super Admin Role'];
        $role = Role::create($role);
        // Set Role Permissions
        // Get all permission, swift through and attach them to the role
        $permission = Permission::get();
        foreach ($permission as $key => $value) {
            $role->attachPermission($value);
        }
        // Create Admin User
        $user = ['fname' => 'Super', 'lname' => 'Admin', 'email' => 'superadmin@admin.com', 'password' => Hash::make('admin12345')];
        $user = User::create($user);
        // Set User Role
        $user->attachRole($role);

        $other_roles = [
            [
                'name' => 'admin', 
                'display_name' => 'Admin', 
                'description' => 'Admin Role'
            ],
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

        $order_time = [
            ['00:00 - 01:00'],
            ['01:00 - 02:00'],
            ['02:00 - 03:00'],
            ['03:00 - 04:00'],
            ['04:00 - 05:00'],
            ['05:00 - 06:00'],
            ['06:00 - 07:00'],
            ['07:00 - 08:00'],
            ['08:00 - 09:00']
        ];
        $driver_notes = [
            ['Hole in pant'],
            ['Button is missing'],
            ['Very Rough pant']
        ];
        $online_chat = [
            'time'  => '9am - 11pm',
            'url'   => 'https://www.online_chat.com',
        ];

        $appDefaults = [
            'VAT' => 5,
            'delivery_charge' => 105,
            'order_time' => json_encode($order_time),
            'driver_notes' => json_encode($driver_notes),
            'FAQ_link' => 'https://www.faq.com',
            'online_chat' => json_encode($online_chat),
            'hotline_contact' => '+9779808225547',
            'company_email' => '+9779808225547',
            'company_logo' => 'company_logo.png'
        ];

        AppDefault::create($appDefaults);
    }
}
