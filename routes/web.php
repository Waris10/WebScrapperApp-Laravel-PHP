<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/chat', function () {
//  return view('chat');
//})->middleware(['auth', 'verified'])->name('chat.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [ScraperController::class, 'index'])->name('chat.index');
    Route::post('/scraper/scrape', [ScraperController::class, 'scrape'])->name('scraper.scrape')->middleware(['throttle:scraper']);
    Route::delete('/scraper/delete/{id}', [ScraperController::class, 'destroy'])->name('scraper.delete');
    Route::get('/scraper/export/csv/{id}', [ExportController::class, 'exportCsv'])->name('scraper.export.csv');
    Route::get('/scraper/export/json/{id}', [ExportController::class, 'exportJson'])->name('scraper.export.json');
    Route::get('/scraper/export/xml/{id}', [ExportController::class, 'exportXml'])->name('scraper.export.xml');
    Route::get('/scraper/export/pdf/{id}', [ExportController::class, 'exportPdf'])->name('scraper.export.pdf');
});

require __DIR__ . '/auth.php';