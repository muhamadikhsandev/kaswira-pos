<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_faktur');  // Tambahan nomor faktur
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->integer('modal');  // Harga modal per item
            $table->integer('total');  // Total penjualan untuk item tersebut
            $table->string('kasir');

            // Kolom untuk menyimpan tanggal & jam transaksi
            $table->dateTime('transaction_date')->nullable();

            // Hapus timestamps agar tidak ada created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans');
    }
}
