<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
   public function run()
    {
        $user = User::create([
          'name' => 'Vedat Bozkurt',
          'email' => 'a@a.com',
          'role' => '1',
          'password' => bcrypt('123')
        ]);

        $role = Role::create(['name' => 'Admin','guard_name' => 'wedat']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
