<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationFactory extends Factory
{
    protected $model = Medication::class;
    
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Omeprazol', 
                'Losartán', 'Metformina', 'Atorvastatina', 'Diclofenaco',
                'Cetirizina', 'Loratadina', 'Salbutamol', 'Captopril',
                'Ranitidina', 'Azitromicina', 'Ciprofloxacino'
            ]) . ' ' . fake()->randomElement(['500mg', '250mg', '100mg', '50mg', '20mg']),
            'lot_number' => fake()->bothify('LOT-####??###'),
        ];
    }
}
