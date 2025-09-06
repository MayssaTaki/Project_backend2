<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'طبي']);
        Category::create(['name' => 'رياضيات ']);
        Category::create(['name' => 'كيمياء']);
        Category::create(['name' => 'انكليزي']);
        Category::create(['name' => 'فرنسي']);
        Category::create(['name' => 'فيزياء']);
        Category::create(['name' => 'علوم طبيعية ']);
        Category::create(['name' => 'نفسي']);
       Category::create(['name' => 'فلك']);
         Category::create(['name' => 'ديني']);



    }
}
