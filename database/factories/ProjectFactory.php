<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Type;
use App\Models\Status;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory {

    protected $model = Project::class;

    public function definition() {
      $startdate = $this->faker->dateTimeThisYear;
    return [
        'name' => $this->faker->firstName,
        'description' => $this->faker->text,
        'type_id' => Type::inRandomOrder()->value('id'),
        'status_id' => Status::inRandomOrder()->value('id'),
        'repo_link' => 'https://github.com/'.$this->faker->firstName,
        'max_member_count' => $this->faker->numberBetween(5, 10),
        'startdate' => $startdate,
        'enddate' => $this->faker->dateTimeBetween($startdate , '1 years'),
        'review' => $this->faker->text
    ];
}
}
