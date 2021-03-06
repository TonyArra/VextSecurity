<?php

use Illuminate\Database\Migrations\Migration;
use Qlcorp\VextFramework\VextBlueprint;
use Illuminate\Support\Facades\Config;

class CreateBrandTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		VextSchema::create('brand', function(VextBlueprint $table) {
            $table->increments('id');

            $table->string('company');
            $table->string('url');
            $table->string('logo') //File path to logo image
                  ->nullable();

            $table->integer('organization_id');
            $table->foreign('organization_id')
                  ->references('id')->on(Config::get('auth.table'))
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
		Schema::dropIfExists('brand');
	}

}