<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreateDepartmentRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('department_role', function(VextBlueprint $table) {
            $table->increments('id')
                  ->gridConfig(array(
                    'text' => 'ID',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->integer('department_id')
                  ->gridConfig(array(
                    'text' => 'Department ID',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Department ID',
                  ));
            $table->foreign('department_id')
                  ->references('id')->on('user')
                  ->onDelete('cascade');

            $table->integer('role_id')
                  ->gridConfig(array(
                    'text' => 'Role ID',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Role ID',
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
		Schema::dropIfExists('department_role');
	}

}