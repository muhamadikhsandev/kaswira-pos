<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class AdminSatuanController extends Controller
{
    /**
     * Menampilkan daftar satuan.
     */
    public function index()
    {
        // Mengambil semua data satuan dengan urutan ascending (data lama di atas, data baru di bawah)
        $satuans = Satuan::orderBy('created_at', 'asc')->get();
        return view('admin.satuan.index', compact('satuans'));
    }

    /**
     * Menyimpan satuan baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Normalisasi: lowercase dan trim
        $nameNormalized = strtolower(trim($validatedData['name']));
        // Cek apakah satuan dengan nama yang sama sudah ada
        $existing = Satuan::whereRaw('LOWER(name) = ?', [$nameNormalized])->first();
        if ($existing) {
            return response()->json([
                'error' => 'Satuan sudah ada!'
            ], 422);
        }

        // Buat data satuan baru
        $satuan = Satuan::create($validatedData);

        return response()->json([
            'success' => 'Satuan berhasil ditambahkan!',
            'satuan'  => $satuan,
        ]);
    }

    /**
     * Memperbarui data satuan.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Normalisasi: lowercase dan trim
        $nameNormalized = strtolower(trim($validatedData['name']));
        // Cek apakah data dengan nama tersebut sudah ada (selain data yang sedang diupdate)
        $existing = Satuan::whereRaw('LOWER(name) = ?', [$nameNormalized])
            ->where('id', '!=', $id)
            ->first();
        if ($existing) {
            return response()->json([
                'error' => 'Satuan sudah ada!'
            ], 422);
        }

        // Temukan data satuan berdasarkan id dan perbarui
        $satuan = Satuan::findOrFail($id);
        $satuan->update($validatedData);

        return response()->json([
            'success' => 'Satuan berhasil diperbarui!',
            'satuan'  => $satuan,
        ]);
    }

    /**
     * Menghapus data satuan.
     */
    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return response()->json([
            'success' => 'Satuan berhasil dihapus!'
        ]);
    }
}
