<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrukController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKasirController;
use App\Http\Controllers\Admin\AdminstokbarangController;
use App\Http\Controllers\Admin\AdminlaporanpenjualanController;
use App\Http\Controllers\Admin\AdminpengaturanController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminSatuanController;
use App\Http\Controllers\Admin\AdminPrintController;

// Post Controller (pastikan file dan namespace-nya benar)
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| 0) Redirect ke halaman login jika belum login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| 1) ROUTE LOGIN DENGAN BREEZE (Untuk guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| 2) ROUTE ADMIN (Middleware auth & admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

    // Profile
    Route::prefix('dashboard/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
        Route::patch('/photo', [ProfileController::class, 'updateProfilePicture'])->name('admin.profile.updatePhoto');
        Route::patch('/info', [ProfileController::class, 'updateProfileInfo'])->name('admin.profile.updateInfo');
    });

    // Stok Barang
    Route::resource('dashboard/stokbarang', AdminstokbarangController::class)->names([
        'index'   => 'admin.stokbarang.index',
        'create'  => 'admin.stokbarang.create',
        'store'   => 'admin.stokbarang.store',
        'edit'    => 'admin.stokbarang.edit',
        'update'  => 'admin.stokbarang.update',
        'destroy' => 'admin.stokbarang.destroy',
    ]);
    Route::get('dashboard/stokbarang/import', [AdminstokbarangController::class, 'importForm'])->name('admin.stokbarang.import.form');
    Route::post('dashboard/stokbarang/import', [AdminstokbarangController::class, 'import'])->name('admin.stokbarang.import');
    Route::post('dashboard/stokbarang/reset-all', [AdminstokbarangController::class, 'resetAll'])->name('admin.stokbarang.resetAll');
    Route::post('dashboard/check-low-stock', [AdminstokbarangController::class, 'checkLowStock'])->name('admin.dashboard.checkLowStock');

    // Upload Struk Gambar
    Route::post('struk/upload', [StrukController::class, 'uploadImage'])->name('admin.struk.upload');

    // Kasir
    Route::prefix('dashboard/kasir')->group(function () {
        Route::get('/', [AdminKasirController::class, 'index'])->name('admin.kasir.index');
        Route::post('store-transaction', [AdminKasirController::class, 'storeTransaction'])->name('admin.kasir.storeTransaction');
        Route::post('simpan', [AdminKasirController::class, 'saveTransaction'])->name('kasir.simpan');
        Route::delete('/{id}', [AdminKasirController::class, 'hapus'])->name('admin.kasir.hapus');
        Route::get('print-struk', [AdminKasirController::class, 'printStruk'])->name('admin.kasir.printStruk');
        Route::get('/kasir/pdf', [AdminKasirController::class, 'downloadPdf'])->name('admin.kasir.barcodepdf');
    });

    // Cetak dan Preview Struk
    Route::post('print/struk', [AdminPrintController::class, 'printReceipt'])->name('admin.print.struk');
    Route::post('print/preview', [AdminPrintController::class, 'previewReceipt'])->name('admin.preview.struk');

    // Laporan Penjualan
    Route::get('dashboard/laporan-penjualan', [AdminlaporanpenjualanController::class, 'index'])->name('laporan.index');
    Route::get('dashboard/laporan-penjualan/filter', [AdminlaporanpenjualanController::class, 'filter'])->name('laporan.filter');
    Route::get('dashboard/laporan-penjualan/export', [AdminlaporanpenjualanController::class, 'exportExcel'])->name('laporan.exportExcel');
    Route::get('dashboard/laporan-penjualan/export-pdf', [AdminlaporanpenjualanController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('dashboard/laporanpenjualan', [AdminlaporanpenjualanController::class, 'index'])->name('admin.laporanpenjualan.index');

    // Pengaturan
    Route::prefix('dashboard/pengaturan')->group(function () {
        Route::get('/', [AdminpengaturanController::class, 'index'])->name('admin.pengaturan.index');
        Route::post('/', [AdminpengaturanController::class, 'update'])->name('admin.pengaturan.update');
    });

    // Kategori
    Route::prefix('dashboard/kategori')->group(function () {
        Route::get('/', [AdminKategoriController::class, 'index'])->name('admin.kategori.index');
        Route::post('/', [AdminKategoriController::class, 'store'])->name('admin.kategori.store');
        Route::put('/{id}', [AdminKategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('/{id}', [AdminKategoriController::class, 'destroy'])->name('admin.kategori.destroy');
        Route::delete('reset', [AdminKategoriController::class, 'resetAll'])->name('admin.kategori.reset');
    });

    // Satuan
    Route::prefix('dashboard/satuan')->group(function () {
        Route::get('/', [AdminSatuanController::class, 'index'])->name('admin.satuan.index');
        Route::post('/', [AdminSatuanController::class, 'store'])->name('admin.satuan.store');
        Route::put('/{id}', [AdminSatuanController::class, 'update'])->name('admin.satuan.update');
        Route::delete('/{id}', [AdminSatuanController::class, 'destroy'])->name('admin.satuan.destroy');
        Route::delete('reset', [AdminSatuanController::class, 'resetAll'])->name('admin.satuan.reset');
    });

    // Posts Resource
    Route::resource('dashboard/posts', PostController::class);
});

/*
|--------------------------------------------------------------------------
| 3) ROUTE KASIR (Di luar admin)
|--------------------------------------------------------------------------
*/
Route::get('/kasir', [AdminKasirController::class, 'index'])->name('kasir.index');
Route::get('/kasir/search', [AdminKasirController::class, 'search'])->name('kasir.search');

/*
|--------------------------------------------------------------------------
| 4) ROUTE AUTENTIKASI LAIN
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
