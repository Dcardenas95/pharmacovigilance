<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = \App\Models\Order::all();
        $medications = \App\Models\Medication::all();
        
        foreach ($orders as $order) {
            $itemCount = rand(1, 5);
            for ($i = 0; $i < $itemCount; $i++) {
                \App\Models\OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'medication_id' => $medications->random()->id,
                ]);
            }
        }
    }
}
