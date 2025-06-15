<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class AdminPrintController extends Controller
{
    public function printReceipt(Request $request)
    {
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pengaturan toko tidak ditemukan.'
            ], 404);
        }

        $noFaktur = $request->input('nomor_faktur');
        if (!$noFaktur) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Nomor faktur wajib dikirim saat mencetak.'
            ], 400);
        }

        $tanggal   = $request->input('tanggal');
        $waktu     = $request->input('waktu');
        $total     = (int)$request->input('total', 0);
        $bayar     = (int)$request->input('bayar', 0);
        $kembalian = (int)$request->input('kembalian', 0);
        $items     = $request->input('items', []);
        $namaKasir = $request->input('nama_kasir', 'Kasir');

        if (!is_array($items)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format item tidak valid.'
            ], 400);
        }

        try {
            if (!empty($pengaturan->printer_name)) {
                try {
                    $connector = new WindowsPrintConnector($pengaturan->printer_name);
                    $printer = new Printer($connector);

                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->setTextSize(2, 2);
                    $printer->text(strtoupper($pengaturan->store_name) . "\n");
                    $printer->setTextSize(1, 1);
                    $printer->text($pengaturan->store_address . "\n");
                    $printer->text("Telp: " . $pengaturan->store_contact . "\n");
                    $printer->text("Pemilik: " . $pengaturan->store_owner . "\n");
                    $printer->text(str_repeat('-', 32) . "\n");

                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("$noFaktur\n");
                    $printer->text("Kasir: $namaKasir\n");
                    $printer->text("Tgl : $tanggal   Jam: $waktu\n");
                    $printer->text(str_repeat('-', 32) . "\n");

                    foreach ($items as $index => $item) {
                        $no       = $index + 1;
                        $name     = $item['name'] ?? '';
                        $quantity = $item['quantity'] ?? 0;
                        $price    = number_format((int)($item['total'] ?? 0), 0, ',', '.');
                        $printer->text("$no. $name\n");
                        $printer->text("     Qty: $quantity   Harga: Rp$price\n");
                    }

                    $printer->text(str_repeat('-', 32) . "\n");
                    $printer->text("Total     : Rp" . number_format($total, 0, ',', '.') . "\n");
                    $printer->text("Bayar     : Rp" . number_format($bayar, 0, ',', '.') . "\n");
                    $printer->text("Kembalian : Rp" . number_format($kembalian, 0, ',', '.') . "\n");
                    $printer->text(str_repeat('-', 32) . "\n");

                    $footerUcapan = "~ " . ($pengaturan->receipt_message ?: 'Terima Kasih') . " ~";
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text(str_pad($footerUcapan, 32, ' ', STR_PAD_BOTH) . "\n");

                    $printer->feed(3);
                    $printer->cut();
                    $printer->close();

                    return response()->json(['status' => 'success']);

                } catch (\Exception $e) {
                    \Log::error("Gagal mencetak struk: " . $e->getMessage());
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Printer terkonfigurasi tetapi gagal mencetak. Pastikan printer menyala dan terhubung.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Printer belum dikonfigurasi di pengaturan toko.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Error sistem saat mencetak: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat mencetak struk.'
            ]);
        }
    }

    public function previewReceipt(Request $request)
    {
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            return response()->json(['error' => 'Pengaturan toko tidak ditemukan.'], 404);
        }

        $tanggalInput = $request->input('tanggal') ?? now()->toDateString();
        $tanggalCarbon = Carbon::parse($tanggalInput)->startOfDay();

        $lastFaktur = Laporan::whereDate('transaction_date', $tanggalCarbon)
            ->where('nomor_faktur', 'like', "KSW-{$tanggalCarbon->format('Ymd')}%")
            ->orderByDesc('nomor_faktur')
            ->value('nomor_faktur');

        $lastNumber = 0;
        if ($lastFaktur && preg_match('/(\d{4})$/', $lastFaktur, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $nextUrutan = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $kodeUnik   = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $noFaktur   = "KSW-{$tanggalCarbon->format('Ymd')}-{$kodeUnik}-{$nextUrutan}";

        $itemsInput = $request->input('items');
        $items = [];

        if (is_string($itemsInput)) {
            try {
                $items = json_decode($itemsInput, true) ?? [];
            } catch (\Exception $e) {
                $items = [];
            }
        } elseif (is_array($itemsInput)) {
            $items = $itemsInput;
        }

        $itemsWithNumber = [];
        foreach ($items as $index => $item) {
            $item['number'] = $index + 1;
            $itemsWithNumber[] = $item;
        }

        $data = [
            'no_faktur' => $noFaktur,
            'urutan'    => Laporan::whereDate('transaction_date', $tanggalCarbon)->count() + 1,
            'tanggal'   => $tanggalInput,
            'waktu'     => $request->input('waktu'),
            'total'     => $request->input('total'),
            'bayar'     => $request->input('bayar'),
            'kembalian' => $request->input('kembalian'),
            'namaKasir' => $pengaturan->cashier_name,
            'items'     => $itemsWithNumber
        ];

        $html = view('admin.kasir.preview', compact('pengaturan', 'data'))->render();

        return response()->json(['html' => $html]);
    }
}
