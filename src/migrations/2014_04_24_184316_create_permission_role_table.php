<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreatePermissionRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('permission_role', function(VextBlueprint $table) {
            $table->increments('id');

            $table->integer('permission_id');
            $table->foreign('permission_id')
                  ->references('id')->on('permission')
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
		Schema::dropIfExists('permission_role');
	}

}