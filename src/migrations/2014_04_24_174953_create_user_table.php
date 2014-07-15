<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /*
         * todo: appends method for text and formName
         * todo: have it added to fillable and JSON
         * todo: change enum type to 'string' via VextBlueprint
         */

		VextSchema::create('user', function(VextBlueprint $table) {
            $table->increments('id')
                  ->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled' => true
                  ));

            $table->string('name')->fillable()->required()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Name'
                  ));

            $table->enum('type', array('user', 'organization', 'department'))
                  ->fillable()
                  ->default('user')
                  ->fieldConfig(array(
                    'fieldLabel' => 'Organization'
                  ));

            //User-Only Fields:

            $table->string('email')->fillable()->nullable()
                  ->fillable()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Email'
                  ));

            $table->string('password', 60)->nullable()
                  ->fillable()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Password'
                  ));

            $table->string('remember_token', 100)->nullable();

            $table->tree();

            $table->appends('text', 'formName');
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
	}

}