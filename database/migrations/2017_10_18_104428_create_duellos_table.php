<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDuellosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duellos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gonderen_id');
            $table->integer('rakip_id');
            $table->integer('soru_id');
            $table->integer('kazanan')->nullable();
            $table->integer('son_cevaplama_zamani');
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
        Schema::dropIfExists('duellos');
    }
}
