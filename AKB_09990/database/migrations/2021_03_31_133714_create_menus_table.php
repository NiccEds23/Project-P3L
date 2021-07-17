<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bahan');
            $table->string('nama_menu');
            $table->string('deskripsi_menu');
            $table->string('kategori_menu');
            $table->string('unit_menu');
            $table->double('harga_menu');
            $table->string('img_url');
            $table->timestamps();

            // $table->foreign('id_bahan')->references('id')->on('bahans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
