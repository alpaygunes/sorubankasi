<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSorusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sorus', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('sorumetni');
            $table->integer('konu_id');
            $table->string('ekleyen');
            $table->string('soru_turu');
            $table->string('yanit','1');
            $table->string('zorluk','1');
            $table->string('sinif','2');
            $table->integer('market_sorusu','1');
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
        Schema::dropIfExists('sorus');
    }
}
