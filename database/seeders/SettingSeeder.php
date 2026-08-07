<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarif chauffeur par jour
        Setting::updateOrCreate(
            ['key' => 'driverDailyRate'],
            ['value' => '1000'] // FCFA
        );

        // TVA en pourcentage
        Setting::updateOrCreate(
            ['key' => 'tvaRate'],
            ['value' => 0.18] // 18%
        );
        Setting::updateOrCreate(
            ['key' => 'reductionRate'],
            ['value' => 0.0] // 0%
        );
    }
}
