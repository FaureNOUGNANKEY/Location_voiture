<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Panne;

class PanneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Panne::create([
            'car_id'=> 1,
            'description' => 'Problème moteur',
            'priority'    => 'Urgente',
            'status'      => 'En attente',
            'panneAmount' => 250000,
        ]);

        Panne::create([
            'car_id'=> 2,
            'description' => 'Freins usés',
            'priority'    => 'Moyenne',
            'status'      => 'En réparation',
            'panneAmount' => 120000,
        ]);

        Panne::create([
            'car_id'=> 3,
            'description' => 'Changement de pneus',
            'priority'    => 'Faible',
            'status'      => 'Réparé',
            'panneAmount' => 80000,
        ]);

        Panne::create([
            'car_id'=> 4,
            'description' => 'Batterie déchargée',
            'priority'    => 'Urgente',
            'status'      => 'En attente',
            'panneAmount' => 60000,
        ]);

        Panne::create([
            'car_id'=> 5,
            'description' => 'Vitres électriques défectueuses',
            'priority'    => 'Moyenne',
            'status'      => 'En réparation',
            'panneAmount' => 40000,
        ]);
    }
}
