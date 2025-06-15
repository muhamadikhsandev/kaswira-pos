<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi ke Barang
    public function barangs()
    {
        // Field 'kategori' di tabel barang adalah foreign key yang mereferensikan id di tabel categories
        return $this->hasMany(Barang::class, 'kategori');
    }
}

