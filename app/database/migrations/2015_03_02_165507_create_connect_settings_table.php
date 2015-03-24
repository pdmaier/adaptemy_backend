<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnectSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connect_settings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('access_key');
			$table->string('api_user');
			$table->string('api_pwd');
			$table->string('default_pwd');
			$table->string('default_group');
			$table->string('default_organization');
			$table->string('rit_server');
			$table->string('det_knowledge_perma');
			$table->string('knowledge_perma');
			$table->string('knowledge_details_perma');
			$table->string('learn_perma');
			$table->string('details_perma');
			$table->string('handshake_service_url');
			$table->string('registration_service_url');
			$table->string('delete_user_service');
			$table->string('chg_group_service_url');
			$table->string('add_org_to_pers_service_url');
			$table->string('prod_avail_service_url');
			$table->string('prod_used_service_url');
			$table->string('prod_objectives_service_url');
			$table->string('learn_service_url');
			$table->string('analyse_obj_service_url');
			$table->string('get_obj_details_service');
			$table->string('knowledge_standard_service_url');
			$table->string('knowledge_details_service_url');
			$table->string('deliver_content_service_url');
			$table->string('det_knowledge_service_url');
			$table->string('practice_service_url');
			$table->string('set_user_settings');
			$table->string('retrieve_no_questions');
			$table->string('create_grouping');
			$table->string('add_obj_to_grouping');
			$table->string('delete_grouping');
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
		Schema::drop('connect_settings');
	}

}
