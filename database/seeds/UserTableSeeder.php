<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;class UserTableSeeder extends Seeder
{
  public function run()
  {
    $role_user = Role::where('name', 'user')->first();
    $role_admin  = Role::where('name', 'admin')->first();    
    $role_superadmin  = Role::where('name', 'superadmin')->first();  

    $employee = new User();
    $employee->name = 'Normal User';
    $employee->email = 'employee@example.com';
    $employee->password = bcrypt('viewfinder');
    $employee->save();
    $employee->roles()->attach($role_user);    

    $manager = new User();
    $manager->name = 'Admin Name';
    $manager->email = 'manager@example.com';
    $manager->password = bcrypt('viewfinder');
    $manager->save();
    $manager->roles()->attach($role_admin);    

    $manager = new User();
    $manager->name = 'Alex Tweedie';
    $manager->email = 'viewfinder.junglist@gmail.com';
    $manager->password = bcrypt('viewfinder');
    $manager->save();
    $manager->roles()->attach($role_superadmin);

  }
}