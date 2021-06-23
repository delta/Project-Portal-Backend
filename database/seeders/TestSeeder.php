<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\StackFactory;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StatusSeeder::class,
            TypeSeeder::class,
            StackSeeder::class
        ]);
    }
}
