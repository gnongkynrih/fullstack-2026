<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create permissions
        Permission::create(['name' => 'frontoffice']);
        Permission::create(['name' => 'waiter']);
        Permission::create(['name' => 'admin']);

        //create roles and assign permissions
        $role = Role::create(['name' => 'frontoffice']);
        $role->givePermissionTo('frontoffice');

        $role = Role::create(['name' => 'waiter']);
        $role->givePermissionTo('waiter');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['admin','frontoffice','waiter']);

        //create permissions
        Permission::create(['name' => 'report']);
        $role = Role::create(['name' => 'report']);
        $role->givePermissionTo('report');

        //find the role admin and assign report to admin role
        $role = Role::findByName('admin');
        $role->givePermissionTo('report');

        $role = Role::findByName('frontoffice');
        $role->givePermissionTo('report');

    }
}
