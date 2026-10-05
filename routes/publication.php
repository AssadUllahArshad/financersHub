<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/disclaimer', 'pages.disclaimer')->name('disclaimer');
Route::view('/editorial-policy', 'pages.editorial-policy')->name('editorial-policy');
Route::get('/faq', FaqController::class)->name('faq');
Route::get('/', [PublicationController::class, 'home'])->name('home');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::get('/search', [PublicationController::class, 'search'])->name('search');
Route::view('/terms', 'pages.terms')->name('terms');
Route::get('/articles/{slug}', [PublicationController::class, 'article'])->where('slug', '[a-z0-9-]+')->name('articles.show');
Route::get('/categories/{slug}', [PublicationController::class, 'category'])->where('slug', '[a-z0-9-]+')->name('categories.show');
Route::get('/authors/{slug}', [PublicationController::class, 'author'])->where('slug', '[a-z0-9-]+')->name('authors.show');
Route::post('/contact', [ReaderController::class, 'contact'])->middleware('throttle:5,60')->name('contact.store');
Route::post('/newsletter/subscribe', [ReaderController::class, 'subscribe'])->middleware('throttle:5,60')->name('newsletter.subscribe');
Route::get('/authors', [PublicationController::class, 'authors'])->name('authors.index');
Route::view('/tools/compound-interest-calculator', 'publication.calculator')->name('tools.compound-interest');
