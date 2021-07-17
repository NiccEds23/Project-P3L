<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pembayaran');
            $table->string('jenis_kartu');
            $table->string('nomor_kartu');
            $table->string('nama_pemilik_kartu');
            $table->timestamps();

            // $table->foreign('id_pembayaran')->references('id')->on('pembayarans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_pembayarans');
    }
}
