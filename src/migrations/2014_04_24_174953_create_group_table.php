<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreateGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('group', function(VextBlueprint $table) {
            $table->increments('id')
                  ->gridConfig(array(
                    'text' => 'Id',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->string('text')->fillable()->required()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Name'
                  ));

            $table->boolean('organization')->fillable()
                  ->default(false)
                  ->fillable()
                  ->gridConfig(array(
                    'text' => 'Organization',
                    'width' => 100
                  ))->fieldConfig(array(
                    'fieldLabel' => 'Organization'

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
        Schema::dropIfExists('user');
        Schema::dropIfExists('brand');
		Schema::dropIfExists('group');
	}

}