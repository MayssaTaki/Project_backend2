<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA'); 

        foreach (range(11, 25) as $i) { 
            $index = $i - 10; 

            $user = User::create([
                'name' => $faker->name,
                'email' => "teacher{$i}@example.com",
                'password' => Hash::make('Password123'),
                'role' => 'teacher',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'specialization' => $faker->randomElement([
                    'رياضيات', 'فيزياء', 'كيمياء', 'لغة عربية', 'لغة إنجليزية', 'أحياء', 'علوم الحاسوب'
                ]),
                'Previous_experiences' => $faker->sentence(10),
                'phone' => $faker->phoneNumber,
                'profile_image' => null,
                'country' => $faker->country,
                'city' => $faker->city,
                'gender' => $faker->randomElement(['male', 'female']),
                'status' => $index <= 5 ? 'rejected' : ($index <= 10 ? 'pending' : 'approved'),
            ]);
        }
    }
}
