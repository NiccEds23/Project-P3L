<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryStokBahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_stok_bahans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bahan');
            $table->integer('id_karyawan');
            $table->date('tanggal_history');
            $table->integer('jumlah_masuk')->nullable();
            $table->integer('jumlah_keluar')->nullable();
            $table->integer('jumlah_terbuang')->nullable();
            $table->integer('jumlah_sisa')->nullable();
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
        Schema::dropIfExists('history_stok_bahans');
    }
}
