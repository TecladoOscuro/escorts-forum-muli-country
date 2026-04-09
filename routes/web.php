<?php

use App\Http\Controllers\AgeVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscortController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

// Language switch
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['de', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back(fallback: '/');
})->name('locale.switch');

// Age verification
Route::get('/age-verification', [AgeVerificationController::class, 'show'])->name('age-verification');
Route::post('/age-verify', [AgeVerificationController::class, 'verify'])->name('age-verify');

// Legal pages (accessible without age verification)
Route::get('/impressum', [LegalController::class, 'impressum'])->name('impressum');
Route::get('/datenschutz', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/forenregeln', [LegalController::class, 'rules'])->name('rules');

// All other routes require age verification
Route::middleware('age.verified')->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Auth
    Route::middleware('guest')->group(function () {
        Route::get('/anmelden', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/anmelden', [AuthController::class, 'login']);
        Route::get('/registrieren', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/registrieren', [AuthController::class, 'register']);
    });

    Route::post('/abmelden', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Escorts
    Route::get('/escorts', [EscortController::class, 'index'])->name('escorts.index');
    Route::get('/escorts/{escortProfile}', [EscortController::class, 'show'])->name('escorts.show');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{category}', [ForumController::class, 'category'])->name('forum.category');
    Route::get('/forum/{category}/{thread}', [ForumController::class, 'thread'])->name('forum.thread');
    Route::middleware('auth')->group(function () {
        Route::get('/forum/{category}/neu', [ForumController::class, 'create'])->name('forum.create');
        Route::post('/forum/{category}', [ForumController::class, 'store'])->name('forum.store');
        Route::post('/forum/{category}/{thread}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    });

    // Reviews
    Route::get('/bewertungen', [ReviewController::class, 'index'])->name('reviews.index');
    Route::middleware('auth')->group(function () {
        Route::get('/bewertung/{escortProfile}/schreiben', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/bewertung/{escortProfile}', [ReviewController::class, 'store'])->name('reviews.store');
    });

    // Search
    Route::get('/suche', [SearchController::class, 'index'])->name('search');

    // Auth-required routes
    Route::middleware('auth')->group(function () {
        // Messages
        Route::get('/nachrichten', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/nachrichten/{conversation}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/nachrichten/{user}', [MessageController::class, 'store'])->name('messages.store');
        Route::post('/nachrichten/{conversation}/reply', [MessageController::class, 'reply'])->name('messages.reply');

        // Tokens
        Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
        Route::post('/tokens/kaufen/{tokenPackage}', [TokenController::class, 'purchase'])->name('tokens.purchase');
        Route::post('/tokens/ausgeben', [TokenController::class, 'spend'])->name('tokens.spend');
    });
});
