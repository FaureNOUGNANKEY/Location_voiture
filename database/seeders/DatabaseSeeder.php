<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Reservation;
use App\Models\Panne;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([CategorySeeder::class, CarSeeder::class,DriverSeeder::class, ReservationSeeder::class ,UserSeeder::class,PanneSeeder::class]);

    }
}
