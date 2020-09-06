<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('statuses')->count() === 0) {
            DB::table('statuses')->insert([
                ['name' => 'PROPOSED'],
                ['name' => 'ONGOING'],
                ['name' => 'PAUSED'],
                ['name' => 'COMPLETED'],
                ['name' => 'ENHANCEMENTS']
            ]);
        }
    }
}
