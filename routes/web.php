<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AtributController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PredictController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasyarakatController;
use App\Http\Controllers\HasilKlasifikasiController;

// Define common route patterns
const ROUTE_LIST = '/list';
const ROUTE_ID = '/{id}';

Route::get("/", function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/classification-data', [DashboardController::class, 'getClassificationData'])->name('classification-data');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Predict Routes
    Route::prefix('predict')->name('predict.')->group(function () {
        Route::get(ROUTE_LIST, [PredictController::class, 'list'])->name('list');
        Route::get(ROUTE_ID, [PredictController::class, 'show'])->name('show');

        Route::post('/run-script', [PredictController::class, 'runPythonScript'])->name('run-script');
    });

    // Data Masyarakat Routes
    $dataMasyarakatBase = '/data-masyarakat';
    Route::prefix($dataMasyarakatBase)->name('data-masyarakat.')->group(function () {
        Route::get('/', [DataMasyarakatController::class, 'index'])->name('index');
        Route::get(ROUTE_LIST, [DataMasyarakatController::class, 'list'])->name('list');
        Route::get('/create', [DataMasyarakatController::class, 'create'])->name('create');
        Route::post('/', [DataMasyarakatController::class, 'store'])->name('store');
        Route::get(ROUTE_ID, [DataMasyarakatController::class, 'show'])->name('show');
        Route::get(ROUTE_ID . '/edit', [DataMasyarakatController::class, 'edit'])->name('edit');
        Route::put(ROUTE_ID, [DataMasyarakatController::class, 'update'])->name('update');
        Route::delete(ROUTE_ID, [DataMasyarakatController::class, 'destroy'])->name('destroy');
        // Import routes
        Route::get('/template/download', [ImportController::class, 'downloadTemplate'])->name('template.download');
        Route::post('/import', [ImportController::class, 'import'])->name('import');
    });

    // Atribut Routes
    $atributBase = '/atribut';
    Route::prefix($atributBase)->name('atribut.')->group(function () {
        Route::get('/', [AtributController::class, 'index'])->name('index');
        Route::get(ROUTE_LIST, [AtributController::class, 'list'])->name('list');
        Route::get('/create', [AtributController::class, 'create'])->name('create');
        Route::post('/', [AtributController::class, 'store'])->name('store');
        Route::get(ROUTE_ID, [AtributController::class, 'show'])->name('show');
        Route::get(ROUTE_ID . '/edit', [AtributController::class, 'edit'])->name('edit');
        Route::put(ROUTE_ID, [AtributController::class, 'update'])->name('update');
        Route::delete(ROUTE_ID, [AtributController::class, 'destroy'])->name('destroy');
    });

    // Hasil Klasifikasi Routes
    Route::get('/hasil-klasifikasi', [HasilKlasifikasiController::class, 'index'])->name('hasil-klasifikasi');

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
        Route::get('/evaluations', [LaporanController::class, 'evaluations'])->name('evaluations');
    });
});
