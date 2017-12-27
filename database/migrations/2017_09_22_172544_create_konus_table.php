<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('konu_adi');
            $table->integer('on_sayfada_listele');
            $table->integer('parent_id');
            $table->date('baslangic_tarihi');
            $table->date('bitis_tarihi');
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
        Schema::dropIfExists('konus');
    }
}
