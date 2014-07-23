<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;
use Illuminate\Support\Facades\Config;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /*
         * todo: have it added to fillable and JSON
         */
        $table = Config::get('auth.table', 'user');

		VextSchema::create($table, function(VextBlueprint $table) {
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
                    'fieldLabel' => 'Type'
                  ));

            //User-Only Fields:

            $table->string('email')->fillable()->nullable()
                  ->fillable()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Email'
                  ));

            $table->string('password', 60)->nullable()->fillable()
                  ->fillable()
                  ->fieldConfig(array(
                    'fieldLabel' => 'Password'
                  ));

            $table->string('remember_token', 100)->nullable();

            $table->tree();

            $table->appends('text', 'string')->fillable()
                ->fieldConfig(array(
                    'allowBlank'=>'false',
                    'fieldLabel'=>'Name'
                ));
            $table->appends('formName', 'string')->fillable();

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists(Config::get('auth.table', 'user'));
        Schema::dropIfExists('brand');
	}

}