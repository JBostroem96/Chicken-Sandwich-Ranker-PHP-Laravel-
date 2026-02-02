<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChickenSandwich;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChickenSandwich>
 */
class ChickenSandwichFactory extends Factory
{   
    protected $model = ChickenSandwich::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => $this->faker->name(),
            'company' => $this->faker->company(), 
            'image' => 'test-image.jpg',
            'logo' => 'test-logo.jpg',
        
        ];
    }
}
