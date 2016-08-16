<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;
use Illuminate\Support\Facades\Config;

class CreateUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = Config::get('auth.table', 'user');

        VextSchema::create($table, function (VextBlueprint $table) {
            $table->model(Config::get('auth.model', 'User'));

            $table->increments('id')
                ->fieldConfig(array(
                    'fieldLabel' => 'Id',
                    'disabled'   => true
                ));

            $table->string('first_name')
                ->fillable();

            $table->string('last_name')
                ->fillable();

            $table->string('middle_name')
                ->fillable();;

            $table->enum('type', array('user', 'organization', 'department'))
                ->fillable()
                ->default('user')
                ->fieldConfig(array(
                    'fieldLabel' => 'Type'
                ));

            //User-Only Fields:

            $table->string('email')
                ->fillable()
                ->fieldConfig(array(
                    'fieldLabel' => 'Email'
                ));

            $table->string('password', 60)->nullable()
                ->fieldConfig(array(
                    'fieldLabel' => 'Password'
                ));

            $table->string('remember_token', 100)->nullable();

            $table->integer('viewport')
                ->nullable()
                ->fillable()
                ->fieldConfig(array(
                    'fieldLabel' => 'Viewport'
                ))->dropdown(array(
                    1 => 'Admin',
                    2 => 'Carrier',
                    3 => 'Physician'
                ));

            $table->boolean('top_level')->fillable()->nullable()
                ->fieldConfig(array(
                    'fieldLabel' => 'Top-Level'
                ));

            $table->string('org_type')
                ->fillable()
                ->dropdown(array('carrier', 'facility', 'pharmacy'))
                ->nullable();

            $table->tree();

            $table->appends('text', 'string')->fillable()
                ->fieldConfig(array(
                    'allowBlank' => 'false',
                    'fieldLabel' => 'Name'
                ));

            $table->appends('name', 'string');

            $table->string('banner')
                ->fillable()
                ->nullable();

            $table->text('login_history')
                ->default('')
                ->fillable();

            $table->appends('formName', 'string');
            $table->timestamps();
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