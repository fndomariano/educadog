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
        return [
            'activity_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'description' =>  $this->faker->randomHtml(5,5),
            'score' => mt_rand(1, 10),
            'pet_id' => mt_rand(1, 10)
        ];
    }
}
