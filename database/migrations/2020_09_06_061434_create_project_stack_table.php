<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectStackTable extends Migration {

	public function up()
	{
		Schema::create('project_stack', function(Blueprint $table) {
			$table->integer('project_id')->unsigned();
			$table->integer('stack_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('project_stack');
	}
}

?>
