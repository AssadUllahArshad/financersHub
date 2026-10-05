<?php

use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::get('/404', fn () => abort(404))->name('404');
Route::get('/500', fn () => abort(500))->name('500');

require __DIR__.'/auth.php';
require __DIR__.'/legacy.php';


Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');



Route::get('/sitemap.xml', [DiscoveryController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [DiscoveryController::class, 'robots'])->name('robots');

Route::middleware(['auth', 'can:studio'])->prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

Route::get('/ads.txt', function () {
    $publisher = \App\Models\SiteSetting::where('key', 'adsense_publisher_id')->value('value');
    $body = preg_match('/^pub-[0-9]{16}$/', $publisher ?? '') ? 'google.com, '.$publisher.', DIRECT, f08c47fec0942fa0'."\n" : "# No advertising sellers configured.\n";

    return response($body)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('ads-txt');


Route::group([], base_path('routes/publication.php'));
Route::prefix('es')->name('es.')->group(base_path('routes/publication.php'));
