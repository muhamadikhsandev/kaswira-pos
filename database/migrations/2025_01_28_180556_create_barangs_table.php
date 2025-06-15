<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('kode_barang', 50)->unique(); // Kode barang
            $table->string('kategori', 100); // Kategori barang
            $table->string('merek', 100); // Merek barang
            $table->string('nama_produk', 150); // Nama produk
            $table->decimal('harga_beli', 15, 2); // Harga beli
            $table->decimal('harga_jual', 15, 2); // Harga jual
            $table->string('satuan', 50); // Satuan barang
            $table->integer('stok')->default(0); // Stok barang
            $table->timestamps(); // Created_at dan Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
