<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ManajemenData\GarduController;
use App\Http\Controllers\Admin\ManajemenData\OmtPengukuranController;
use App\Http\Controllers\Admin\ManajemenData\PemeliharaanController;
use App\Http\Controllers\Autentikasi\AuthController;
use App\Http\Controllers\CetakPdf\CetakGarduPdfController;
use App\Http\Controllers\CetakPdf\CetakPemeliharaanPdfController;
use App\Http\Controllers\CetakPdf\CetakPengukuranPdfController;

/**
 * --- AUTH & DASHBOARD ---
 */
Route::get('/', fn () => view('welcome'));

Route::get('/login', fn () => view('auth.login_form'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', fn () => view('manajemen-data.dashboard'))
    ->middleware('auth')->name('dashboard');


/**
 * --- GARDU ---
 * admin = full CRUD
 * petugas/tamu = read-only
 */
Route::prefix('manajemen-data/gardu')
    ->name('gardu.')
    ->middleware('auth')
    ->group(function () {

        // Write: admin
        Route::middleware('role:admin')->group(function () {
            Route::get('/create',     [GarduController::class, 'create'])->name('create');
            Route::post('/',          [GarduController::class, 'store'])->name('store');
            Route::get('/{id}/edit',  [GarduController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('/{id}',       [GarduController::class, 'update'])->whereNumber('id')->name('update');
            Route::delete('/{id}',    [GarduController::class, 'destroy'])->whereNumber('id')->name('destroy');
        });

        // Read-only: admin, petugas, tamu
        Route::middleware('role:admin,petugas,tamu')->group(function () {
            Route::get('/', [GarduController::class, 'index'])->name('index');

            // === QR PAGE + API (letakkan sebelum route parameter) ===
            Route::get('/qr', [GarduController::class, 'qr'])->name('qr'); // halaman scan QR
            Route::post('/find-by-kode', [GarduController::class, 'findByKode'])->name('findByKode'); // API cari kd_gardu

            // Rekap historis (per KD Gardu)
            Route::get('/history', [GarduController::class, 'historyIndex'])->name('history.index');

            // Detail historis untuk satu gardu (berdasarkan id_data_gardu)
            // PENTING: taruh SEBELUM '/{id}' agar tidak bentrok
            Route::get('/history/{id}', [GarduController::class, 'historyShow'])
                ->whereNumber('id')
                ->name('history.show');

            Route::get('/{id}/cetak-pdf', [CetakGarduPdfController::class, 'cetak'])
                ->whereNumber('id')
                ->name('cetak.pdf.gardu');

            Route::get('/{id}', [GarduController::class, 'show'])
                ->whereNumber('id')
                ->name('show');
        });
    });


/**
 * --- OMT PENGUKURAN ---
 * admin & petugas = CRUD
 * tamu = read-only
 * (Sekaligus route "Pengukuran" dari tombol di form edit gardu)
 */
Route::middleware(['auth', 'role:admin,petugas,tamu'])->group(function () {
    Route::get(
        'manajemen-data/omt-pengukuran/history',
        [OmtPengukuranController::class, 'historyIndex']
    )->name('omt-pengukuran.history.index');

    // Detail historis untuk satu OMT (dipakai di tombol "Lihat Riwayat" pada rekap)
    Route::get(
        'manajemen-data/omt-pengukuran/history/{id}',
        [OmtPengukuranController::class, 'historyShow']
    )->whereNumber('id')->name('omt-pengukuran.history.show');

    // (opsional) JSON semua history
    Route::get(
        'manajemen-data/omt-pengukuran/history/json',
        [OmtPengukuranController::class, 'historyAllJson'] // pastikan method-nya ada
    )->name('omt-pengukuran.history.json');
});


/* ====== CRUD OMT PENGUKURAN ====== */
Route::prefix('manajemen-data/omt-pengukuran')
    ->name('omt-pengukuran.')
    ->middleware('auth')
    ->group(function () {

        // Write: admin & petugas
        Route::middleware('role:admin,petugas')->group(function () {
            // tombol "Pengukuran" dari edit gardu menuju sini
            Route::get('/create/{kd_gardu}', [OmtPengukuranController::class, 'create'])
                ->name('create');

            Route::post('/', [OmtPengukuranController::class, 'store'])
                ->name('store');

            Route::put('/{id}', [OmtPengukuranController::class, 'update'])
                ->whereNumber('id')->name('update');

            Route::delete('/{id}', [OmtPengukuranController::class, 'destroy'])
                ->whereNumber('id')->name('destroy');
        });

        // Read-only: admin, petugas, tamu
        Route::middleware('role:admin,petugas,tamu')->group(function () {
            Route::get('/', [OmtPengukuranController::class, 'index'])
                ->name('index');

            Route::get('/{id}', [OmtPengukuranController::class, 'show'])
                ->whereNumber('id')->name('show');   // batasi numerik

            Route::get('/{id}/cetak-pdf', [CetakPengukuranPdfController::class, 'cetak'])
                ->whereNumber('id')
                ->name('cetak.pdf.pengukuran');
        });
    });


/**
 * --- PEMELIHARAAN ---
 * admin & petugas = CRUD
 * tamu = read-only
 */
Route::prefix('manajemen-data/pemeliharaan')
    ->name('pemeliharaan.')
    ->middleware('auth')
    ->group(function () {

        // Read-only (index & show & history): admin, petugas, tamu
        Route::middleware('role:admin,petugas,tamu')->group(function () {
            Route::get('/', [PemeliharaanController::class, 'index'])->name('index');

            // ====== HISTORY (Rekap + Detail) ======
            // Letakkan SEBELUM '/{id}' agar tidak bentrok
            Route::get('/history', [PemeliharaanController::class, 'historyIndex'])
                ->name('history.index'); // --> view: recap_index.blade
            Route::get('/history/{id}', [PemeliharaanController::class, 'historyShow'])
                ->whereNumber('id')
                ->name('history.show');   // --> view: detail_by_pemeliharaan.blade
            Route::get('/history/json', [PemeliharaanController::class, 'historyAllJson'])
                ->name('history.json');   // opsional

            // Detail & Cetak (harus didefinisikan SETELAH route history)
            Route::get('/{id}/cetak-pdf', [CetakPemeliharaanPdfController::class, 'cetak'])
                ->whereNumber('id')
                ->name('cetak.pdf.pemeliharaan');

            Route::get('/{id}', [PemeliharaanController::class, 'show'])
                ->whereNumber('id')->name('show');
        });

        // Write: admin & petugas
        Route::middleware('role:admin,petugas')->group(function () {
            Route::get('/create/{kd_gardu}', [PemeliharaanController::class, 'create'])
                ->where('kd_gardu', '[A-Za-z0-9\-\._]+')
                ->name('create');

            Route::post('/', [PemeliharaanController::class, 'store'])->name('store');

            Route::get('/{id}/edit', [PemeliharaanController::class, 'edit'])
                ->whereNumber('id')->name('edit');

            Route::put('/{id}', [PemeliharaanController::class, 'update'])
                ->whereNumber('id')->name('update');

            Route::delete('/{id}', [PemeliharaanController::class, 'destroy'])
                ->whereNumber('id')->name('destroy');
        });
    });



/**
 * OPTIONAL: ambil CSRF untuk AJAX
 */
Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]));
