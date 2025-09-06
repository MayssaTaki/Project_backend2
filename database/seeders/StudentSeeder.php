<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA'); 

        foreach (range(1, 10) as $i) {
            $user = User::create([
                'name' => $faker->name,
                'email' => "student{$i}@example.com",
                'password' => Hash::make('Password123'),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'date_of_birth' => $faker->date(),
                'gender' => $faker->randomElement(['male', 'female']),
                'country' => $faker->country,
                'city' => $faker->city,
                'phone' => $faker->phoneNumber,
                'profile_image' => null, 
                'is_banned' => $i <= 2 ? true : false, 
            ]);
        }
    }
}
