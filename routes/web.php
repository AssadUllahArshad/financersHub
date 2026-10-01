<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::view('/404', 'design.404')->name('404');
Route::redirect('/404.html', '/404', 301);
Route::view('/500', 'design.500')->name('500');
Route::redirect('/500.html', '/500', 301);
Route::view('/about', 'design.about')->name('about');
Route::redirect('/about.html', '/about', 301);
Route::get('/articles/bank-account', [PublicationController::class, 'article'])->defaults('slug', 'bank-account')->name('articles.bank-account');
Route::redirect('/articles/bank-account.html', '/articles/bank-account', 301);
Route::get('/articles/business-cash-flow', [PublicationController::class, 'article'])->defaults('slug', 'business-cash-flow')->name('articles.business-cash-flow');
Route::redirect('/articles/business-cash-flow.html', '/articles/business-cash-flow', 301);
Route::get('/articles/credit-interest', [PublicationController::class, 'article'])->defaults('slug', 'credit-interest')->name('articles.credit-interest');
Route::redirect('/articles/credit-interest.html', '/articles/credit-interest', 301);
Route::get('/articles/emergency-fund', [PublicationController::class, 'article'])->defaults('slug', 'emergency-fund')->name('articles.emergency-fund');
Route::redirect('/articles/emergency-fund.html', '/articles/emergency-fund', 301);
Route::get('/articles/financial-app', [PublicationController::class, 'article'])->defaults('slug', 'financial-app')->name('articles.financial-app');
Route::redirect('/articles/financial-app.html', '/articles/financial-app', 301);
Route::get('/articles/index-funds', [PublicationController::class, 'article'])->defaults('slug', 'index-funds')->name('articles.index-funds');
Route::redirect('/articles/index-funds.html', '/articles/index-funds', 301);
Route::get('/articles/insurance-coverage', [PublicationController::class, 'article'])->defaults('slug', 'insurance-coverage')->name('articles.insurance-coverage');
Route::redirect('/articles/insurance-coverage.html', '/articles/insurance-coverage', 301);
Route::get('/articles/loan-questions', [PublicationController::class, 'article'])->defaults('slug', 'loan-questions')->name('articles.loan-questions');
Route::redirect('/articles/loan-questions.html', '/articles/loan-questions', 301);
Route::get('/articles/retirement-planning', [PublicationController::class, 'article'])->defaults('slug', 'retirement-planning')->name('articles.retirement-planning');
Route::redirect('/articles/retirement-planning.html', '/articles/retirement-planning', 301);
Route::get('/articles/saving-investing', [PublicationController::class, 'article'])->defaults('slug', 'saving-investing')->name('articles.saving-investing');
Route::redirect('/articles/saving-investing.html', '/articles/saving-investing', 301);
Route::get('/articles/tax-planning', [PublicationController::class, 'article'])->defaults('slug', 'tax-planning')->name('articles.tax-planning');
Route::redirect('/articles/tax-planning.html', '/articles/tax-planning', 301);
Route::get('/authors/editorial-team', [PublicationController::class, 'author'])->defaults('slug', 'editorial-team')->name('authors.editorial-team');
Route::redirect('/authors/editorial-team.html', '/authors/editorial-team', 301);
Route::get('/categories/banking', [PublicationController::class, 'category'])->defaults('slug', 'banking')->name('categories.banking');
Route::redirect('/categories/banking.html', '/categories/banking', 301);
Route::get('/categories/business', [PublicationController::class, 'category'])->defaults('slug', 'business')->name('categories.business');
Route::redirect('/categories/business.html', '/categories/business', 301);
Route::get('/categories/credit', [PublicationController::class, 'category'])->defaults('slug', 'credit')->name('categories.credit');
Route::redirect('/categories/credit.html', '/categories/credit', 301);
Route::get('/categories/fintech', [PublicationController::class, 'category'])->defaults('slug', 'fintech')->name('categories.fintech');
Route::redirect('/categories/fintech.html', '/categories/fintech', 301);
Route::get('/categories/insurance', [PublicationController::class, 'category'])->defaults('slug', 'insurance')->name('categories.insurance');
Route::redirect('/categories/insurance.html', '/categories/insurance', 301);
Route::get('/categories/investing', [PublicationController::class, 'category'])->defaults('slug', 'investing')->name('categories.investing');
Route::redirect('/categories/investing.html', '/categories/investing', 301);
Route::get('/categories/loans', [PublicationController::class, 'category'])->defaults('slug', 'loans')->name('categories.loans');
Route::redirect('/categories/loans.html', '/categories/loans', 301);
Route::get('/categories/personal-finance', [PublicationController::class, 'category'])->defaults('slug', 'personal-finance')->name('categories.personal-finance');
Route::redirect('/categories/personal-finance.html', '/categories/personal-finance', 301);
Route::get('/categories/retirement', [PublicationController::class, 'category'])->defaults('slug', 'retirement')->name('categories.retirement');
Route::redirect('/categories/retirement.html', '/categories/retirement', 301);
Route::get('/categories/saving', [PublicationController::class, 'category'])->defaults('slug', 'saving')->name('categories.saving');
Route::redirect('/categories/saving.html', '/categories/saving', 301);
Route::get('/categories/taxes', [PublicationController::class, 'category'])->defaults('slug', 'taxes')->name('categories.taxes');
Route::redirect('/categories/taxes.html', '/categories/taxes', 301);
Route::view('/contact', 'design.contact')->name('contact');
Route::redirect('/contact.html', '/contact', 301);
Route::view('/disclaimer', 'design.disclaimer')->name('disclaimer');
Route::redirect('/disclaimer.html', '/disclaimer', 301);
Route::view('/editorial-policy', 'design.editorial-policy')->name('editorial-policy');
Route::redirect('/editorial-policy.html', '/editorial-policy', 301);
Route::get('/faq', [ContentController::class, 'faq'])->name('faq');
Route::redirect('/faq.html', '/faq', 301);
Route::get('/', [PublicationController::class, 'home'])->name('home');
Route::redirect('/index.html', '/', 301);
Route::view('/privacy', 'design.privacy')->name('privacy');
Route::redirect('/privacy.html', '/privacy', 301);
Route::get('/search', [PublicationController::class, 'search'])->name('search');
Route::redirect('/search.html', '/search', 301);
Route::view('/terms', 'design.terms')->name('terms');
Route::redirect('/terms.html', '/terms', 301);

Route::get('/articles/{slug}', [PublicationController::class, 'article'])->where('slug', '[a-z0-9-]+')->name('articles.show');
Route::get('/categories/{slug}', [PublicationController::class, 'category'])->where('slug', '[a-z0-9-]+')->name('categories.show');
Route::get('/authors/{slug}', [PublicationController::class, 'author'])->where('slug', '[a-z0-9-]+')->name('authors.show');

Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');

Route::post('/contact', [ReaderController::class, 'contact'])->middleware('throttle:5,60')->name('contact.store');
Route::post('/newsletter/subscribe', [ReaderController::class, 'subscribe'])->middleware('throttle:5,60')->name('newsletter.subscribe');

Route::get('/authors', [PublicationController::class, 'authors'])->name('authors.index');

Route::get('/sitemap.xml', [DiscoveryController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [DiscoveryController::class, 'robots'])->name('robots');
Route::redirect('/admin/login', '/login');

Route::middleware(['auth', 'can:studio'])->prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

Route::get('/ads.txt', function () {
    $publisher = \App\Models\SiteSetting::where('key', 'adsense_publisher_id')->value('value');
    $body = preg_match('/^pub-[0-9]{16}$/', $publisher ?? '') ? 'google.com, '.$publisher.', DIRECT, f08c47fec0942fa0'."\n" : "# No advertising sellers configured.\n";

    return response($body)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('ads-txt');

Route::view('/tools/compound-interest-calculator', 'publication.calculator')->name('tools.compound-interest');
