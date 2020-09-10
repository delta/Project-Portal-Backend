<?php

namespace Tests;
use Illuminate\Support\Facades\Artisan;

trait MigrateAndSeedOnce
{
    /**
    * If true, setup has run at least once.
    * @var boolean
    */
    protected static $setUpHasRunOnce = false;
    /**
    * After the first run of setUp "migrate:fresh --seed"
    * @return void
    */
    public function setUp(): void
    {
        parent::setUp();

            Artisan::call(
                'migrate:fresh'
            );
            Artisan::call(
                'db:seed', ['--class' => 'UnitTestSeeder']
            );
            static::$setUpHasRunOnce = true;

    }
}
