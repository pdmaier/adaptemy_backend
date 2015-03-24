<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSessionTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_session_tokens', function(Blueprint $table)
		{
			$table->integer('id_token', true);
			$table->integer('id_user');
			$table->string('token');
			$table->string('rit_token');
			$table->timestamps();
			$table->dateTime('deleted_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_session_tokens');
	}

}
