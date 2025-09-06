<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        $this->call([
             \Database\Seeders\UserSeeder::class,
    \Database\Seeders\CategorySeeder::class,
     \Database\Seeders\StudentSeeder::class,
     \Database\Seeders\TeacherSeeder::class,

            
        ]);
    }
}