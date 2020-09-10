<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\DB::table('types')->count() === 0) {
            \DB::table('types')->insert([
                array('name' => 'ADMIN'),
                array('name' => 'PERSONAL'),
                array('name' => 'DWOC'),
                array('name' => 'FESTS')
            ]);
        }
    }
}
