<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StrukController extends Controller
{
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('image') || !$request->has('items')) {
            return response()->json(['error' => 'Gambar atau data item tidak ditemukan'], 400);
        }

        $file = $request->file('image');

        if (!$file->isValid()) {
            return response()->json(['error' => 'File gambar tidak valid'], 400);
        }

        $path = $file->store('struks', 'public');
        $imageUrl = url('storage/' . $path); // GANTI asset() → url()

        Log::info('Struk berhasil diupload', [
            'file_path' => $path,
            'image_url' => $imageUrl,
        ]);

        $tanggal     = $request->input('tanggal');
        $waktu       = $request->input('waktu');
        $nomorWA     = $request->input('nomor');
        $nomorFaktur = $request->input('nomor_faktur', 'F-' . uniqid());

        $kasirName = $request->input('kasir_name');
        if (empty($kasirName)) {
            $user = Auth::user();
            $kasirName = $user ? ($user->profile->name ?? $user->name) : 'System';
        }

        $modalPerItem = 0;
        $items = json_decode($request->input('items'), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($items)) {
            Storage::disk('public')->delete($path);
            return response()->json(['error' => 'Format data item tidak valid'], 400);
        }

        $processedItems = [];
        foreach ($items as $item) {
            if (!isset($item['name']) || !isset($item['quantity']) || !isset($item['total'])) {
                Log::warning('Item tidak lengkap, dilewati', $item);
                continue;
            }

            $processedItems[] = [
                'nomor_faktur'     => $nomorFaktur,
                'kode_barang'      => $item['kode_barang'] ?? 'N/A',
                'nama_barang'      => $item['name'],
                'jumlah'           => $item['quantity'],
                'modal'            => $item['modal'] ?? $modalPerItem,
                'total'            => $item['total'],
                'kasir'            => $kasirName,
                'transaction_date' => $tanggal . ' ' . $waktu,
            ];
        }

        return response()->json([
            'image_url' => $imageUrl,
            'message'   => 'Struk berhasil diupload',
            'items'     => $processedItems,
        ]);
    }
}
