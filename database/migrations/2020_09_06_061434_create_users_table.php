<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('roll_number');
			$table->string('name', 255);
			$table->string('github_link', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}

?>
