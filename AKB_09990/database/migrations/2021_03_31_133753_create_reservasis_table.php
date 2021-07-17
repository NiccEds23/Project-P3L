<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_customer');
            $table->integer('id_meja');
            $table->integer('id_karyawan');
            $table->string('sesi_reservasi');
            $table->date('tanggal_kunjung_reservasi');
            $table->string('status_reservasi');
            $table->timestamps();

            // $table->foreign('id_customer')->references('id')->on('customers');
            // $table->foreign('id_meja')->references('id')->on('mejas');
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
        Schema::dropIfExists('reservasis');
    }
}
