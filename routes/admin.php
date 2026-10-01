<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::get('/advertisements', [OperationsController::class, 'advertising'])->name('advertisements');
Route::redirect('/advertisements.html', '/admin/advertisements', 301);
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
Route::redirect('/articles.html', '/admin/articles', 301);
Route::get('/authors', [ContentController::class, 'index'])->defaults('kind', 'authors')->name('authors');
Route::redirect('/authors.html', '/admin/authors', 301);
Route::get('/categories', [ContentController::class, 'index'])->defaults('kind', 'categories')->name('categories');
Route::redirect('/categories.html', '/admin/categories', 301);
Route::view('/comments', 'cms.inactive', ['service' => 'Comments'])->name('comments');
Route::redirect('/comments.html', '/admin/comments', 301);
Route::get('/editor', [ArticleController::class, 'editor'])->name('editor');
Route::redirect('/editor.html', '/admin/editor', 301);
Route::get('/faq', [ContentController::class, 'index'])->defaults('kind', 'faq')->name('faq');
Route::redirect('/faq.html', '/admin/faq', 301);
Route::get('', [OperationsController::class, 'dashboard'])->name('dashboard');
Route::redirect('/index.html', '/admin', 301);
Route::get('/media', [MediaController::class, 'index'])->name('media');
Route::redirect('/media.html', '/admin/media', 301);
Route::get('/newsletter', [ReaderController::class, 'newsletter'])->name('newsletter');
Route::redirect('/newsletter.html', '/admin/newsletter', 301);
Route::get('/seo', [OperationsController::class, 'seo'])->name('seo');
Route::redirect('/seo.html', '/admin/seo', 301);
Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
Route::redirect('/settings.html', '/admin/settings', 301);
Route::get('/tags', [ContentController::class, 'index'])->defaults('kind', 'tags')->name('tags');
Route::redirect('/tags.html', '/admin/tags', 301);
Route::get('/articles/{article}/edit', [ArticleController::class, 'editor'])->name('articles.edit');
Route::post('/articles', [ArticleController::class, 'save'])->name('articles.store');
Route::put('/articles/{article}', [ArticleController::class, 'save'])->name('articles.update');
Route::post('/articles/{article}/transition', [ArticleController::class, 'transition'])->name('articles.transition');
Route::get('/articles/{article}/preview', [ArticleController::class, 'preview'])->name('articles.preview');
Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
Route::post('/articles/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');
Route::post('/articles/{article}/revisions/{revision}', [ArticleController::class, 'revision'])->name('articles.revision');
Route::post('/content/{kind}', [ContentController::class, 'save'])->name('content.store');
Route::put('/content/{kind}/{id}', [ContentController::class, 'save'])->name('content.update');
Route::delete('/content/{kind}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
Route::post('/media', [MediaController::class, 'store'])->name('media.store');
Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::post('/settings', [ContentController::class, 'saveSettings'])->name('settings.save');
Route::get('/contacts', [ReaderController::class, 'contacts'])->middleware('can:manage-settings')->name('contacts');
Route::post('/contacts/{message}/resolve', [ReaderController::class, 'resolve'])->middleware('can:manage-settings')->name('contacts.resolve');
Route::post('/seo', [OperationsController::class, 'saveSeo'])->middleware('can:manage-settings')->name('seo.save');
Route::post('/contacts/{message}/read', [ReaderController::class, 'read'])->middleware('can:manage-settings')->name('contacts.read');
Route::post('/contacts/{message}/retry', [ReaderController::class, 'retry'])->middleware(['can:manage-settings', 'throttle:5,1'])->name('contacts.retry');
Route::get('/maintenance', [MaintenanceController::class, 'index'])->middleware('can:manage-settings')->name('maintenance');
Route::post('/maintenance', [MaintenanceController::class, 'run'])->middleware(['can:manage-settings', 'throttle:3,1'])->name('maintenance.run');

Route::post('/advertisements', [OperationsController::class, 'saveAdvertising'])->middleware('can:manage-settings')->name('advertisements.save');

Route::get('/content/{kind}/create', [ContentController::class, 'form'])->name('content.create');
Route::get('/content/{kind}/{id}/edit', [ContentController::class, 'form'])->name('content.edit');

Route::post('/contacts/{message}/reopen', [ReaderController::class, 'reopen'])->middleware('can:manage-settings')->name('contacts.reopen');

Route::get('/analytics', [\App\Http\Controllers\VisitorAnalyticsController::class, 'index'])->middleware('can:manage-settings')->name('analytics');
