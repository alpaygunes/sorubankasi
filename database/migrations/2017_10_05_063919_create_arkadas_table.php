<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArkadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arkadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('arkadas_id');
            $table->integer('engelle');
            $table->integer('arkadaslik_istegi');
            $table->integer('listemde_gosterme');
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
        Schema::dropIfExists('arkadas');
    }
}
