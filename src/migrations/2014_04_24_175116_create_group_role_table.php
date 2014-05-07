<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreateGroupRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('group_role', function(VextBlueprint $table) {
            $table->increments('id');

            $table->integer('group_id');
            $table->foreign('group_id')
                  ->references('id')->on('group')
                  ->onDelete('cascade');

            $table->integer('role_id');
            $table->foreign('role_id')
                  ->references('id')->on('role')
                  ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('group_role');
	}

}