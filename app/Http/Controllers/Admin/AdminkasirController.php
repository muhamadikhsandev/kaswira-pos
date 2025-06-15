<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Laporan;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminkasirController extends Controller
{
    /**
     * Menampilkan halaman kasir dengan data barang dan pengaturan toko.
     */
    public function index()
    {
        // Ambil data barang
        $barangs = Barang::select('kode_barang', 'nama_produk', 'harga_beli', 'harga_jual')->get();
        
        // Ambil data pengaturan toko secara dinamis
        $pengaturan = Pengaturan::first();

        return view('admin.kasir.index', compact('barangs', 'pengaturan'));
    }

    /**
     * Generate invoice number berdasarkan tanggal hari ini dan sequence terakhir.
     */
protected function generateNoFaktur(): string
{
    $today = Carbon::today();

    // Ambil nomor urut terbesar 4 digit terakhir nomor faktur hari ini
    $lastSequence = Laporan::whereDate('transaction_date', $today)
        ->where('nomor_faktur', 'like', 'KSW-' . $today->format('Ymd') . '%')
        ->selectRaw('MAX(CAST(SUBSTRING(nomor_faktur, -4) AS UNSIGNED)) as max_sequence')
        ->value('max_sequence');

    $lastSequence = $lastSequence ?: 0;
    $nextSequence = $lastSequence + 1;

    // Format nomor faktur: KSW-YYYYMMDD-HHmmss-XXXX (4 digit sequence)
    return 'KSW-' . Carbon::now()->format('Ymd-His') . '-' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
}


    

    /**
     * Mencari barang berdasarkan kode_barang atau nama_produk via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->query('query');

        $barangs = Barang::select('kode_barang', 'nama_produk', 'harga_beli', 'harga_jual')
            ->where('kode_barang', 'like', "%{$query}%")
            ->orWhere('nama_produk', 'like', "%{$query}%")
            ->get();

        return response()->json($barangs);
    }

    /**
     * Menyimpan transaksi kasir ke database laporan dan mengurangi stok barang.
     */
    public function saveTransaction(Request $request)
    {
        $data = $request->validate([
            'items'   => 'required|array',
            'total'   => 'required|numeric',
            'payment' => 'required|numeric',
        ]);

        $transactionDate = Carbon::now()->format('Y-m-d H:i:s');
        $nomorFaktur = $this->generateNoFaktur();

        $user = auth()->user();
        $kasirName = isset($user->profile) && !empty($user->profile->name)
            ? $user->profile->name
            : $user->name;

        DB::beginTransaction();
        try {
            foreach ($data['items'] as $item) {
                $barang = Barang::where('kode_barang', $item['code'] ?? '')
                    ->orWhere('nama_produk', $item['name'])
                    ->first();

                if (!$barang) {
                    throw new \Exception("Barang {$item['name']} tidak ditemukan.");
                }

                if ($barang->stok < $item['quantity']) {
                    throw new \Exception("Stok untuk {$barang->nama_produk} tidak mencukupi. Tersisa: {$barang->stok}.");
                }

                $barang->stok -= $item['quantity'];
                $barang->save();

                Laporan::create([
                    'kode_barang'      => $barang->kode_barang,
                    'nama_barang'      => $barang->nama_produk,
                    'jumlah'           => $item['quantity'],
                    'modal'            => $barang->harga_beli,
                    'total'            => $item['total'],
                    'kasir'            => $kasirName,
                    'transaction_date' => $transactionDate,
                    'nomor_faktur'     => $nomorFaktur,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'nomor_faktur' => $nomorFaktur,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    
    

    /**
     * Meng-generate file PDF yang berisi hanya barcode.
     */
    public function downloadPdf(Request $request)
    {
        try {
            // Ambil kode_barang saja
            $barangs = Barang::select('kode_barang')->get();

            $pdf = Pdf::loadView('admin.kasir.barcodepdf', [
                'barangs' => $barangs,
                'pdf'     => true
            ]);

            if ($request->ajax()) {
                return response($pdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="barcode_list.pdf"');
            }
            return $pdf->download('barcode_list.pdf');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal meng-generate PDF: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal meng-generate PDF: ' . $e->getMessage()]);
        }
    }
}