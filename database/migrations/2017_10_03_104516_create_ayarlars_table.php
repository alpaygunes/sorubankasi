<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAyarlarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayarlars', function (Blueprint $table) {
            $table->integer('zeka_ve_mantik_sorulari_id');

            $table->integer('fiyat_zm_cok_kolay');// zm = zeka ve mantık soruları
            $table->integer('fiyat_zm_kolay');
            $table->integer('fiyat_zm_normal');
            $table->integer('fiyat_zm_zor');
            $table->integer('fiyat_zm_cok_zor');

            $table->integer('fiyat_ds_cok_kolay');// ds = ders soruları
            $table->integer('fiyat_ds_kolay');
            $table->integer('fiyat_ds_normal');
            $table->integer('fiyat_ds_zor');
            $table->integer('fiyat_ds_cok_zor');

            $table->integer('soru_basina_sure')->default(30);

            $table->increments('id');
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
        Schema::dropIfExists('ayarlars');
    }
}
