<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusesTable extends Migration {

	public function up()
	{
		Schema::create('statuses', function(Blueprint $table) {
			$table->increments('id');
			$table->enum('name', array('PROPOSED', 'ONGOING', 'PAUSED', 'COMPLETED', 'ENHANCEMENTS'));
		});
	}

	public function down()
	{
		Schema::drop('statuses');
	}
}

?>
