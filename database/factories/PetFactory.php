<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $petNames = ['Scooby', 'Mel', 'Pluto', 'Banzé', 'King', 'Galeguinha', 'Bob', 'Princesa'];
        $breeds = ['Labrador', 'SRD', 'Golden', 'Cocker', 'Poodle', 'Schnauzer', 'Beagle', 'Pastor alemão'];

        return [
            'name' => $petNames[array_rand($petNames)],
            'breed' => $breeds[array_rand($breeds)],            
            'customer_id' => random_int(1, 15)
        ];
    }
}
