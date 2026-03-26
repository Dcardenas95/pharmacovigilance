<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Order::factory()->count(20)->create();
    }
}
