<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreateRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('role', function(VextBlueprint $table) {
            $table->increments('id')
                  ->gridConfig(array('text' => 'Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->tree();

            $table->string('title')
                  ->gridConfig(array('text' => 'Title',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Title',
                  ));
         });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('role');
	}

}