<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreatePermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('permission', function(VextBlueprint $table) {
            $table->increments('id')
                  ->gridConfig(array('text' => 'Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->string('operation')->fillable()->required()
                  ->gridConfig(array('text' => 'Operation',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Operation',
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
		Schema::dropIfExists('permission');
	}

}