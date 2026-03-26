<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Medication::factory()->count(20)->create();
    }
}
