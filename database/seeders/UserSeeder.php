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

        // إنشاء معلمين
       /*  for ($i = 0; $i < 5; $i++) {
            $teacherUser = User::create([
                'email' => 'teacher' . ($i + 1) . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
            ]);

            Teacher::factory()->create(['user_id' => $teacherUser->id]);
        }

        // إنشاء طلاب
        for ($i = 0; $i < 20; $i++) {
            $studentUser = User::create([
                'email' => 'student' . ($i + 1) . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]);

            Student::factory()->create(['user_id' => $studentUser->id]);
        }*/
    }
}