<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_reservasi');
            $table->integer('id_karyawan');
            $table->string('kode_pembayaran');
            $table->string('status_pembayaran');
            $table->string('jenis_pembayaran')->nullable();
            $table->string('kode_verifikasi')->nullable();
            $table->double('jumlah_pembayaran');
            $table->time('waktu_pembayaran');
            $table->date('tanggal_pembayaran');
            $table->timestamps();

            // $table->foreign('id_reservasi')->references('id')->on('reservasis');
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
        Schema::dropIfExists('pembayarans');
    }
}
