<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanExport;
use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AdminLaporanPenjualanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::orderBy('transaction_date', 'desc')->get();
        $totalTerjual    = $laporans->sum('jumlah');
        $totalTransaksi  = $laporans->sum('total');
        $totalKeuntungan = $laporans->sum(fn($l) => $l->total - ($l->modal * $l->jumlah));

        return view('admin.laporanpenjualan.index', compact(
            'laporans', 'totalTerjual', 'totalTransaksi', 'totalKeuntungan'
        ));
    }

    public function filter(Request $request)
    {
        $q = Laporan::query();

        if ($request->filled('tanggal')) {
            $q->whereDate('transaction_date', $request->tanggal);
        }

        if ($request->filled('minggu')) {
            [$year, $week] = explode('-W', $request->minggu);
            $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();
            $q->whereBetween('transaction_date', [$start, $end]);
        }

        if ($request->filled('bulan')) {
            $year  = substr($request->bulan, 0, 4);
            $month = substr($request->bulan, 5, 2);
            $q->whereYear('transaction_date', $year)
              ->whereMonth('transaction_date', $month);
        }

        $laporans = $q->orderBy('transaction_date', 'desc')->get();

        return response()->json([
            'laporans' => $laporans->map(function ($laporan) {
                return [
                    'nomor_faktur'     => $laporan->nomor_faktur ?? '',
                    'kode_barang'      => $laporan->kode_barang ?? '',
                    'nama_barang'      => $laporan->nama_barang ?? '',
                    'jumlah'           => $laporan->jumlah ?? 0,
                    'modal'            => ($laporan->modal ?? 0) * ($laporan->jumlah ?? 0),
                    'total'            => $laporan->total ?? 0,
                    'kasir'            => $laporan->kasir ?? '',
                    'transaction_date' => $laporan->transaction_date ?? null,
                ];
            }),
            'totalTerjual' => $laporans->sum('jumlah') ?? 0,
            'totalTransaksi' => $laporans->sum('total') ?? 0,
            'totalKeuntungan' => $laporans->sum(function($l) {
                return ($l->total ?? 0) - (($l->modal ?? 0) * ($l->jumlah ?? 0));
            }) ?? 0
        ]);
    }

    public function exportExcel(Request $request)
    {
        $q = Laporan::query();
        $filename = 'laporan_penjualan_' . date('Ymd_His');

        if ($tgl = $request->get('tanggal')) {
            $filename .= '_harian_' . $tgl;
            $q->whereDate('transaction_date', $tgl);
        }

        if ($minggu = $request->get('minggu')) {
            [$yr, $wk] = explode('-W', $minggu);
            $start = Carbon::now()->setISODate($yr, $wk)->startOfWeek();
            $end   = Carbon::now()->setISODate($yr, $wk)->endOfWeek();
            $filename .= '_mingguan_' . $minggu;
            $q->whereBetween('transaction_date', [$start, $end]);
        }

        if ($bl = $request->get('bulan')) {
            $year  = substr($bl, 0, 4);
            $month = substr($bl, 5, 2);
            $filename .= '_bulanan_' . $bl;
            $q->whereYear('transaction_date', $year)
              ->whereMonth('transaction_date', $month);
        }

        $laporans = $q->orderBy('transaction_date', 'desc')->get();
        $totJ     = $laporans->sum('jumlah');
        $totT     = $laporans->sum('total');
        $totK     = $laporans->sum(fn($l) => $l->total - ($l->modal * $l->jumlah));

        return Excel::download(
            new LaporanExport($laporans, $totJ, $totT, $totK, $filename),
            $filename . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $q = Laporan::query();
        $filename = 'laporan_penjualan_' . date('Ymd_His');

        if ($tgl = $request->get('tanggal')) {
            $filename .= '_harian_' . $tgl;
            $q->whereDate('transaction_date', $tgl);
        }

        if ($minggu = $request->get('minggu')) {
            [$yr, $wk] = explode('-W', $minggu);
            $start = Carbon::now()->setISODate($yr, $wk)->startOfWeek();
            $end   = Carbon::now()->setISODate($yr, $wk)->endOfWeek();
            $filename .= '_mingguan_' . $minggu;
            $q->whereBetween('transaction_date', [$start, $end]);
        }

        if ($bl = $request->get('bulan')) {
            $year  = substr($bl, 0, 4);
            $month = substr($bl, 5, 2);
            $filename .= '_bulanan_' . $bl;
            $q->whereYear('transaction_date', $year)
              ->whereMonth('transaction_date', $month);
        }

        $laporans = $q->orderBy('transaction_date', 'desc')->get();
        $totJ     = $laporans->sum('jumlah');
        $totT     = $laporans->sum('total');
        $totK     = $laporans->sum(fn($l) => $l->total - ($l->modal * $l->jumlah));

        $pdf = Pdf::loadView('admin.laporanpenjualan.export_pdf', [
            'laporans'        => $laporans,
            'totalTerjual'    => $totJ,
            'totalTransaksi'  => $totT,
            'totalKeuntungan' => $totK,
        ]);

        return $pdf->download($filename . '.pdf');
    }
}
