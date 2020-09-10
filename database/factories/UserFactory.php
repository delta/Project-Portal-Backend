<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $rollNumber = $this->faker->unique()->numberBetween(100000000, 109999999);
        return [
            'roll_number' => $rollNumber,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt($rollNumber),
            'github_handle' => 'github.com/' . $this->faker->firstName
        ];
    }
}
