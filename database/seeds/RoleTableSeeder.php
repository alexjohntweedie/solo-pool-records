<?php

use Illuminate\Database\Seeder;
use App\Role;class RoleTableSeeder extends Seeder
{
  public function run()
  {
    $role_employee = new Role();
    $role_employee->name = 'superadmin';
    $role_employee->description = 'A SuperAdmin User';
    $role_employee->save();    

    $role_employee = new Role();
    $role_employee->name = 'admin';
    $role_employee->description = 'An Admin User';
    $role_employee->save();    
    
    $role_manager = new Role();
    $role_manager->name = 'user';
    $role_manager->description = 'A Normal User';
    $role_manager->save();
  }
}