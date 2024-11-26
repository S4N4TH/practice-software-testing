<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialConnectController;
use App\Http\Controllers\TOTPController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/status', function () {
    return response()->json(['version' => config('app.version'), 'environment' => env('APP_ENV'), 'app_name' => env('APP_NAME')], 200,
        ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
});

Route::post('/refresh', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
        '--seed' => null, '--force' => null
    ]);

    \Illuminate\Support\Facades\Artisan::call('invoice:remove');

    return response()->json(['result' => 'refresh done']);
});

Route::controller(BrandController::class)->prefix('brands')->group(function () {
    Route::get('', 'index');
    Route::get('/search', 'search');
    Route::get('/{id}', 'show');
    Route::post('', 'store');
    Route::put('/{id}', 'update');
    Route::patch('/{id}', 'patch');
    Route::delete('/{id}', 'destroy');
});

Route::controller(CartController::class)->prefix('carts')->group(function () {
    Route::post('', 'createCart');
    Route::post('/{id}', 'addItem');
    Route::put('/{id}/product/quantity', 'updateQuantity');
    Route::get('/{id}', 'getCart');
    Route::delete('/{cartId}/product/{productId}', 'removeProductFromCart');
    Route::delete('/{cartId}', 'deleteCart');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/tree', 'indexTree');
    Route::get('', 'index');
    Route::get('/search', 'search');
    Route::get('/tree/{id}', 'show');
    Route::post('', 'store');
    Route::patch('/{id}', 'patch');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::controller(ContactController::class)->prefix('messages')->group(function () {
    Route::post('', 'send');
    Route::post('/{id}/attach-file', 'attachFile');
    Route::get('', 'index');
    Route::get('/{id}', 'show');
    Route::post('/{id}/reply', 'storeReply');
    Route::put('/{id}/status', 'updateStatus');
});

Route::controller(FavoriteController::class)->prefix('favorites')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('/{id}', 'show');
    Route::delete('/{id}', 'destroy');
});

Route::controller(ImageController::class)->prefix('images')->group(function () {
    Route::get('', 'index');
});

Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
    Route::get('', 'index');
    Route::get('/search', 'search');
    Route::get('/{id}', 'show');
    Route::get('/{id}/download-pdf', 'downloadPDF');
    Route::get('/{id}/download-pdf-status', 'downloadPDFStatus');
    Route::put('/{id}/status', 'updateStatus');
    Route::post('', 'store');
    Route::delete('/{id}', 'destroy');
    Route::put('/{id}', 'update');
    Route::patch('/{id}', 'patch');
});

Route::controller(PaymentController::class)->prefix('payment')->group(function () {
    Route::post('/check', 'check');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('', 'index');
    Route::get('/search', 'search');
    Route::get('/{id}', 'show');
    Route::get('/{id}/related', 'showRelated');
    Route::post('', 'store');
    Route::put('/{id}', 'update');
    Route::patch('/{id}', 'patch');
    Route::delete('/{id}', 'destroy');
});

Route::middleware(['throttle:reports'])->controller(ReportController::class)->prefix('reports')->group(function () {
    Route::get('/total-sales-of-years', 'totalSalesOfYears');
    Route::get('/total-sales-per-country', 'totalSalesPerCountry');
    Route::get('/top10-purchased-products', 'top10PurchasedProducts');
    Route::get('/top10-best-selling-categories', 'top10BestSellingCategories');
    Route::get('/customers-by-country', 'customersByCountry');
    Route::get('/average-sales-per-month', 'averageSalesPerMonth');
    Route::get('/average-sales-per-week', 'averageSalesPerWeek');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::post('/login', 'login');
    Route::post('/change-password', 'changePassword');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/register', 'store');
    Route::get('/logout', 'logout');
    Route::get('/search', 'search');
    Route::get('/refresh', 'refresh');
    Route::get('/me', 'me');
    Route::put('{id}', 'update');
    Route::patch('{id}', 'patch');
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::delete('/{id}', 'destroy');
});

Route::controller(SocialConnectController::class)->prefix('auth')->group(function () {
    Route::get('/social-login', 'getAuthUrl');
    Route::get('/cb/google', 'callbackGoogle');
    Route::get('/cb/github', 'callbackGithub');
});

Route::controller(TOTPController::class)->prefix('totp')->group(function () {
    Route::post('/setup', 'setup');
    Route::post('/verify', 'verify');
    Route::post('/login/totp', 'loginWithTOTP');
});
