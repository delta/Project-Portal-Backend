<?php

namespace Database\Factories;

use App\Models\Stack;

use Illuminate\Database\Eloquent\Factories\Factory;

class StackFactory extends Factory
{
    protected $model = Stack::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName
        ];
    }
}
