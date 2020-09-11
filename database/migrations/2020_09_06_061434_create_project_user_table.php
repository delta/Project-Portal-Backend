<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectUserTable extends Migration {

	public function up()
	{
		Schema::create('project_user', function(Blueprint $table) {
			$table->integer('project_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->enum('role', array('AUTHOR', 'DEVELOPER', 'MAINTAINER'));
            $table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('project_user');
	}
}

?>
