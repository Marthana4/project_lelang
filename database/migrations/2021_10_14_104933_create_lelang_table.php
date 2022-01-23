<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLelangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lelang', function (Blueprint $table) {
            $table->BigIncrements('id_lelang');
            $table->UnsignedBigInteger('id_barang');
            $table->Date('tgl_lelang');
            $table->Integer('harga_akhir'); //pakai nullable
            $table->UnsignedBigInteger('id_pengguna'); //nullable
            $table->UnsignedBigInteger('id_petugas');
            $table->Enum('status',['dibuka','ditutup']);

            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->foreign('id_pengguna')->references('id')->on('users');
            $table->foreign('id_petugas')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lelang');
    }
}
