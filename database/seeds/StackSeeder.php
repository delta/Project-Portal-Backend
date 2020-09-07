<?php

use Illuminate\Database\Seeder;

class StackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Stack::class, 20)->create();
    }
}
