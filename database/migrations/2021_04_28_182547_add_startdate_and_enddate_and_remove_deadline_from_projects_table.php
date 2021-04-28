<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartdateAndEnddateAndRemoveDeadlineFromProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->date('startdate')->after('max_member_count')->nullable();
			$table->date('enddate')->after('startdate')->nullable();
            $table->dropColumn('deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('startdate');
            $table->dropColumn('enddate');
            $table->date('deadline')->nullable();
        });
    }
}
