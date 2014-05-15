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
            $table->increments('id')
                  ->gridConfig(array(
                    'text' => 'Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->integer('group_id')
                  ->gridConfig(array(
                    'text' => 'Group_Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Group_Id',
                  ));
            $table->foreign('group_id')
                  ->references('id')->on('group')
                  ->onDelete('cascade');

            $table->integer('role_id')
                  ->gridConfig(array(
                    'text' => 'Role_Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Role_Id',
                  ));
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