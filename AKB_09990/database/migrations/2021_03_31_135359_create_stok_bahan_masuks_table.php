<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBahanMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_bahan_masuks', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bahan');
            $table->integer('id_karyawan');
            $table->integer('jumlah_masuk');
            $table->string('unit_stok_bahan');
            $table->double('harga_stok_bahan');
            $table->date('tanggal_history');
            $table->timestamps();

            // $table->foreign('id_bahan')->references('id')->on('bahans');
            // $table->foreign('id_karyawan')->references('id')->on('karyawans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_bahan_masuks');
    }
}
