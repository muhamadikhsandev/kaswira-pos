<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Satuan;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'kategori', // menyimpan id dari Category
        'merek',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'satuan', // menyimpan id dari Satuan
        'stok',
    ];

    // Relasi ke Category (kategori)
    public function category()
    {
        // Asumsikan field 'kategori' di tabel barang adalah foreign key yang mereferensikan id di tabel categories
        return $this->belongsTo(Category::class, 'kategori');
    }

    // Relasi ke Satuan
    public function satuanRelation()
    {
        // Asumsikan field 'satuan' di tabel barang adalah foreign key yang mereferensikan id di tabel satuans
        return $this->belongsTo(Satuan::class, 'satuan');
    }
}
