<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $role = new Role();
      $role->name = 'Admin';
      $role->save();
  
      // Crear un usuario y asignarle el rol
      $user = new User();
      $user->name = 'Super';
      $user->surname = 'Admin';
      $user->email = 'admin@admin.com';
      $user->password = bcrypt('admin2024');
      $user->type_user = '2';
      $user->role()->associate($role); // Asignar el rol al usuario
      $user->save();
    }
}
