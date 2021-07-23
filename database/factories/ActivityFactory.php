<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $description = sprintf(
            '<p>%s</p><p>%s</p><p>%s</p>', 
            $this->faker->text, 
            $this->faker->text, 
            $this->faker->text
        );

        return [
            'activity_date' => $this->faker->date('Y-m-d', 'now'),
            'description' => $description,
            'score' => mt_rand(1, 10),
            'pet_id' => mt_rand(1, 10)
        ];
    }
}
