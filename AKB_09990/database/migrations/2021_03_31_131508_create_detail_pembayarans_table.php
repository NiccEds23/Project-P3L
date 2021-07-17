<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pembayaran');
            $table->integer('id_menu');
            $table->integer('jumlah_item');
            $table->string('status_pesanan');
            $table->double('total_harga_item');
            $table->timestamps();

            // $table->foreign('id_pembayaran')->references('id')->on('pembayarans');
            // $table->foreign('id_menu')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pembayarans');
    }
}
