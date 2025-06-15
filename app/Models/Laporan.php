<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_faktur',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'modal',
        'total',
        'kasir',
        'transaction_date',
    ];

    // Agar transaction_date otomatis diperlakukan sebagai Carbon instance
    protected $dates = ['transaction_date'];

    // Nonaktifkan penggunaan created_at dan updated_at
    public $timestamps = false;
}
