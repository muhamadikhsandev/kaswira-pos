<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;

class AdminpengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan toko.
     */
    public function index()
    {
        $pengaturan = Pengaturan::first();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Meng-update pengaturan toko.
     */
    public function update(Request $request)
    {
        // Validasi data termasuk field receipt_message
        $data = $request->validate([
            'printer_name'     => 'required|string|max:255',
            'store_name'       => 'required|string|max:255',
            'store_address'    => 'required|string',
            'store_contact'    => 'required|string|max:50',
            'store_owner'      => 'required|string|max:255',
            'receipt_message'  => 'nullable|string', // <- tambahkan ini
        ]);

        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            $pengaturan = new Pengaturan();
        }

        $pengaturan->printer_name     = $data['printer_name'];
        $pengaturan->store_name       = $data['store_name'];
        $pengaturan->store_address    = $data['store_address'];
        $pengaturan->store_contact    = $data['store_contact'];
        $pengaturan->store_owner      = $data['store_owner'];
        $pengaturan->receipt_message  = $data['receipt_message'] ?? null; // <- tambahkan ini
        $pengaturan->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Pengaturan toko berhasil diperbarui.',
            'pengaturan' => $pengaturan
        ]);
    }
}
