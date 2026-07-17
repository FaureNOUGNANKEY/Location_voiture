<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name'       => 'SUV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Berline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Citadine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Pickup',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Sportive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
