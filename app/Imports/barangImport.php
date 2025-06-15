<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Category;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Session;

class BarangImport implements ToModel
{
    public function model(array $row)
    {
        // Lewati baris header jika kolom pertama berisi 'Kode Barang'
        if (isset($row[0]) && $row[0] === 'Kode Barang') {
            return null;
        }

        // Ambil kode barang
        $kode_barang = $row[0] ?? null;
        if (!$kode_barang) {
            return null;
        }

        // Proses Category (cari berdasarkan name)
        $categoryName = $row[1] ?? null;
        $category = null;
        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            if ($category->wasRecentlyCreated) {
                Session::push('kategori_baru', $categoryName);
            }
        }

        // Proses Satuan (kolom ke-7 misalnya)
        $satuanName = $row[6] ?? null;
        $satuan = null;
        if ($satuanName) {
            $satuan = Satuan::firstOrCreate(['name' => $satuanName]);
            if ($satuan->wasRecentlyCreated) {
                Session::push('satuan_baru', $satuanName);
            }
        }

        // CAST nilai harga agar disimpan sebagai integer (tanpa desimal)
        $hargaBeli = isset($row[4]) && is_numeric($row[4]) ? (int)$row[4] : 0;
        $hargaJual = isset($row[5]) && is_numeric($row[5]) ? (int)$row[5] : 0;

        return new Barang([
            'kode_barang' => $kode_barang,
            'kategori'    => $category ? $category->id : null,
            'merek'       => $row[2] ?? null,
            'nama_produk' => $row[3] ?? null,
            'harga_beli'  => $hargaBeli,
            'harga_jual'  => $hargaJual,
            'satuan'      => $satuan ? $satuan->id : null,
            'stok'        => $row[7] ?? 0,
        ]);
    }
}
