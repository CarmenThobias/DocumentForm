<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Books']);
        Category::create(['name' => 'Articles']);
        Category::create(['name' => 'Reports']);
    }
}