<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testdb:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the database to be used for testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $schemaName = config("database.connections.testing.database");
        $charset = config("database.connections.testing.charset",'utf8mb4');
        $collation = config("database.connections.testing.collation",'utf8mb4_unicode_ci');

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        $success = DB::statement($query);
        if ($success) {
            $this->info("Test database created successfully.");
            return 0;
        }
        $this->error("Cannot create test database.");
        return 1;
    }
}
