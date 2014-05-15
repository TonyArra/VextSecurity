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
                  ->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->string('text')->fillable()->required()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Title',
                  ));

            $table->tree();
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