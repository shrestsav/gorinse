<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
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
        // $this->call(UsersTableSeeder::class);
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
        $user = ['name' => 'Super Admin', 'email' => 'superadmin@admin.com', 'password' => Hash::make('admin12345')];
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
    }
}
