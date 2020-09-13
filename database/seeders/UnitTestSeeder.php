<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\StackFactory;

class UnitTestSeeder extends Seeder
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
