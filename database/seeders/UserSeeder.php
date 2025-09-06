<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        
        $admin = User::create([
            'name'=>'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

    
          }
}