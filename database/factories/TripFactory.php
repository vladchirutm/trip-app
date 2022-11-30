<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start_date = $this->faker->dateTimeBetween('this month', '+6 days');
        $end_date = $this->faker->dateTimeBetween($start_date, strtotime('+'.$this->faker->numberBetween(1, 30).' days'));

        $title = $this->faker->unique()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->text(),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location' => $this->faker->country(),
            'price' => $this->faker->randomFloat(),
        ];
    }
}
