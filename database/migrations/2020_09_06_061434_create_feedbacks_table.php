<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbacksTable extends Migration {

	public function up()
	{
		Schema::create('feedbacks', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned()->nullable();
            $table->text('content');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('feedbacks');
	}
}

?>
