<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DemoBladeController;
use App\Http\Controllers\SecurityTestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ValidationLabController;
use App\Http\Controllers\XSSLabController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsrfLabController;

Route::get('/', function () {
    return view('index');
});

// Tickets crud
// Menggunakan Resource Controller dengan Form Request validation
// Store: StoreTicketRequest
// Update: UpdateTicketRequest
Route::resource('tickets', TicketController::class);
// Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
// Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
// Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
// Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
// Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
// Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
// Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

// Demo Blade Templating
Route::prefix('demo-blade')->name('demo-blade.')->group(function () {
    Route::get('/', [DemoBladeController::class, 'index'])->name('index');
    Route::get('/directives', [DemoBladeController::class, 'directives'])->name('directives');
    Route::get('/components', [DemoBladeController::class, 'components'])->name('components');
    Route::get('/includes', [DemoBladeController::class, 'includes'])->name('includes');
    Route::get('/stacks', [DemoBladeController::class, 'stacks'])->name('stacks');
});

// XSS Lab
Route::prefix('xss-lab')->name('xss-lab.')->group(function () {
    Route::get('/', [XSSLabController::class, 'index'])->name('index');
    Route::post('/reset-comments', [XSSLabController::class, 'resetComments'])->name('reset-comments');

    // Reflected XSS
    Route::get('/reflected/vulnerable', [XSSLabController::class, 'reflectedVulnerable'])->name('reflected.vulnerable');
    Route::get('/reflected/secure', [XSSLabController::class, 'reflectedSecure'])->name('reflected.secure');

    // Stored XSS
    Route::get('/stored/vulnerable', [XSSLabController::class, 'storedVulnerable'])->name('stored.vulnerable');
    Route::post('/stored/vulnerable', [XSSLabController::class, 'storedVulnerableStore'])->name('stored.vulnerable.store');
    Route::get('/stored/secure', [XSSLabController::class, 'storedSecure'])->name('stored.secure');
    Route::post('/stored/secure', [XSSLabController::class, 'storedSecureStore'])->name('stored.secure.store');

    // DOM-Based XSS
    Route::get('/dom/vulnerable', [XSSLabController::class, 'domVulnerable'])->name('dom.vulnerable');
    Route::get('/dom/secure', [XSSLabController::class, 'domSecure'])->name('dom.secure');
});

// Comments
Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy')
    ->middleware('auth');

Route::put('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update');

// Security Testing (hanya untuk development!)
Route::prefix('security-testing')->name('security-testing.')->group(function () {
    Route::get('/', [SecurityTestController::class, 'index'])->name('index');
    Route::get('/xss', [SecurityTestController::class, 'xssTest'])->name('xss');
    Route::get('/csrf', [SecurityTestController::class, 'csrfTest'])->name('csrf');
    Route::post('/csrf', [SecurityTestController::class, 'csrfTestPost'])->name('csrf.post');
    Route::get('/headers', [SecurityTestController::class, 'headersTest'])->name('headers');
    Route::get('/audit', [SecurityTestController::class, 'auditChecklist'])->name('audit');
});
// validation lab
Route::prefix('validation-lab')->name('validation-lab.')->group(function () {
    // Index - Menu Lab
    Route::get('/', [ValidationLabController::class, 'index'])
        ->name('index');

    // ----- VULNERABLE FORM -----
    // Form tanpa server-side validation
    Route::get('/vulnerable', [ValidationLabController::class, 'vulnerableForm'])
        ->name('vulnerable');
    Route::post('/vulnerable', [ValidationLabController::class, 'vulnerableSubmit'])
        ->name('vulnerable.submit');
    Route::post('/vulnerable/clear', [ValidationLabController::class, 'vulnerableClear'])
        ->name('vulnerable.clear');

    // ----- SECURE FORM -----
    // Form dengan server-side validation
    Route::get('/secure', [ValidationLabController::class, 'secureForm'])
        ->name('secure');
    Route::post('/secure', [ValidationLabController::class, 'secureSubmit'])
        ->name('secure.submit');
    Route::post('/secure/clear', [ValidationLabController::class, 'secureClear'])
        ->name('secure.clear');
});

// API DEMO (untuk demo bypass dengan curl/Postman)
Route::prefix('api')->group(function () {
    // Vulnerable endpoint - tanpa CSRF dan validation
    Route::post('/vulnerable-submit', [ValidationLabController::class, 'apiVulnerable'])
        ->withoutMiddleware(['web']);
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

// CSRF Lab Routes

Route::prefix('csrf-lab')->name('csrf-lab.')->group(function () {
    // Index - Menu Lab
    Route::get('/', [CsrfLabController::class, 'index'])
        ->name('index');

    // How It Works - Penjelasan CSRF
    Route::get('/how-it-works', [CsrfLabController::class, 'howItWorks'])
        ->name('how-it-works');

    // Attack Demo - Simulasi serangan
    Route::get('/attack-demo', [CsrfLabController::class, 'attackDemo'])
        ->name('attack-demo');

    // Protection Demo - Demo protection
    Route::get('/protection-demo', [CsrfLabController::class, 'protectionDemo'])
        ->name('protection-demo');

    // AJAX Demo - CSRF untuk AJAX
    Route::get('/ajax-demo', [CsrfLabController::class, 'ajaxDemo'])
        ->name('ajax-demo');

    // ----- ACTION ROUTES -----

    // Secure transfer (dengan CSRF protection normal)
    Route::post('/secure-transfer', [CsrfLabController::class, 'secureTransfer'])
        ->name('secure-transfer');

    // Protected action
    Route::post('/protected-action', [CsrfLabController::class, 'protectedAction'])
        ->name('protected-action');

    // AJAX action
    Route::post('/ajax-action', [CsrfLabController::class, 'ajaxAction'])
        ->name('ajax-action');

    // Reset demo data
    Route::post('/reset', [CsrfLabController::class, 'resetDemo'])
        ->name('reset');
});

Route::post('/csrf-lab/vulnerable-transfer', [CsrfLabController::class, 'vulnerableTransfer'])
    ->name('csrf-lab.vulnerable-transfer')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Route untuk demo PROTECTED transfer (DENGAN CSRF - akan return 419 jika tanpa token)
Route::post('/csrf-lab/protected-transfer', [CsrfLabController::class, 'protectedTransfer'])
    ->name('csrf-lab.protected-transfer');
