<?php

use App\Http\Controllers\SaintController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SaintController::class, 'index'])->name('saints.index');
Route::get('/saints/create', [SaintController::class, 'create'])->name('saints.create');
Route::post('/saints', [SaintController::class, 'store'])->name('saints.store');
Route::post('/saints/{saint}', [SaintController::class, 'update'])->name('saints.update');
Route::delete('/saints/{saint}', [SaintController::class, 'destroy'])->name('saints.destroy');
Route::get('/saints/{saint}/qrcode', [SaintController::class, 'generateQRCode'])->name('saints.qrcode');

Route::get('/qrcodescanner', function () {
    return view('saints.qrcodescanner');
})->name('qrcodescanner');

Route::get('/saints/pdf', [SaintController::class, 'generatePDF'])->name('saints.pdf');

Route::get('/csv', function () {
    return view('csv.view');
})->name('csv.view');

Route::get('/saints/export', [SaintController::class, 'exportCSV'])->name('saints.export');
