<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['title' => ' Secretary'],
            ['title' => ' Staff'],
            ['title' => 'Admin'],
            // Add more roles as needed
        ]);
    }
}

