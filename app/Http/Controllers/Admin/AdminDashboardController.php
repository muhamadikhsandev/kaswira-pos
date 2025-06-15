<?php
// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Category;
use App\Models\Satuan;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'namaBarang'      => Barang::count(),
            'stokBarang'      => Barang::sum('stok'),
            'laporanhasil'    => Laporan::sum('total'),
            'barangterjual'   => Laporan::sum('jumlah'),
            'totalLaporan'    => Laporan::count(),
            'categoryBarang'  => Category::count(),
            'totalSatuan'     => Satuan::count(),
            'totalKeuntungan' => Laporan::sum('total') - Laporan::sum(DB::raw('modal * jumlah'))
        ];

        // Query dasar
        $query = Laporan::query();

        // Charts
        $salesPerDay   = (clone $query)
            ->selectRaw('DATE(transaction_date) as date, SUM(total) as total')
            ->groupBy('date')->orderBy('date')->get();

        $salesPerWeek  = (clone $query)
            ->selectRaw('YEAR(transaction_date) as year, WEEK(transaction_date,1) as week, SUM(total) as total')
            ->groupBy('year','week')->orderBy('year')->orderBy('week')->get()
            ->map(fn($r) => [
                'label' => $r->year . '-W' . str_pad($r->week,2,'0',STR_PAD_LEFT),
                'total' => $r->total
            ]);

        $salesPerMonth = (clone $query)
            ->selectRaw("DATE_FORMAT(transaction_date,'%Y-%m') as month, SUM(total) as total")
            ->groupBy('month')->orderBy('month')->get();

        // Produk favorit
        $produkFavorit = DB::table('laporans')
            ->select('nama_barang', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('nama_barang')->orderByDesc('total_terjual')->limit(5)->get();
        $maxJumlah = $produkFavorit->max('total_terjual') ?: 1;

        return view('admin.dashboard.index', compact(
            'data','salesPerDay','salesPerWeek','salesPerMonth','produkFavorit','maxJumlah'
        ));
    }

    public function checkLowStock()
    {
        return response()->json(Barang::where('stok','<=',5)->get());
    }
}
