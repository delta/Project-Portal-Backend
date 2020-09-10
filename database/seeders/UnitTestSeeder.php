<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            TypeSeeder::class
        ]);
    }
}
