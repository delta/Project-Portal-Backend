<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('type_id')->references('id')->on('types')
						->onDelete('restrict')
                        ->onUpdate('restrict');

		});
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('statuses')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->foreign('sender_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->foreign('receiver_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_stack', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_stack', function(Blueprint $table) {
			$table->foreign('stack_id')->references('id')->on('stacks')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_user', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_type_id_foreign');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_status_id_foreign');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->dropForeign('feedbacks_project_id_foreign');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->dropForeign('feedbacks_sender_id_foreign');
		});
		Schema::table('feedbacks', function(Blueprint $table) {
			$table->dropForeign('feedbacks_receiver_id_foreign');
		});
		Schema::table('project_stack', function(Blueprint $table) {
			$table->dropForeign('project_stack_project_id_foreign');
		});
		Schema::table('project_stack', function(Blueprint $table) {
			$table->dropForeign('project_stack_stack_id_foreign');
		});
		Schema::table('project_user', function(Blueprint $table) {
			$table->dropForeign('project_user_project_id_foreign');
		});
		Schema::table('project_user', function(Blueprint $table) {
			$table->dropForeign('project_user_user_id_foreign');
		});
	}
}

?>
