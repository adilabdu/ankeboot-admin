<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ConsignmentReturnController;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\Google\GoogleAuthController;
use App\Http\Controllers\Google\GoogleDocsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreTransferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UpdateTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('/paginate', [BookController::class, 'paginate']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/statistics', [StatisticsController::class, 'books']);
        Route::get('/statistics/book', [StatisticsController::class, 'book']);
        Route::get('/stores', [BookController::class, 'stores']);
        Route::get('/search', [SearchController::class, 'books']);
        Route::post('/', [BookController::class, 'post']);
        Route::post('/update', [BookController::class, 'update']);
        Route::post('/delete', [BookController::class, 'delete']);
        Route::post('/csv', [CSVController::class, 'insertBooks']);
        Route::post('/covers', [ImageController::class, 'uploadBookCovers']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'post']);
        Route::post('/update/{transaction}', [UpdateTransactionController::class, 'index']);
        Route::post('/csv', [CSVController::class, 'insertTransactions']);
    });

    Route::prefix('daily-sales')->group(function () {
        Route::get('/', [DailySaleController::class, 'index']);
        Route::post('/', [DailySaleController::class, 'create']);
        Route::post('/update', [DailySaleController::class, 'update']);
        Route::get('/statistics', [StatisticsController::class, 'dailySales']);
        Route::post('/csv', [CSVController::class, 'insertDailySaleTransactions']);
    });

    Route::prefix('daily-sales')->group(function () {
        Route::get('/', [DailySaleController::class, 'index']);
        Route::post('/', [DailySaleController::class, 'create']);
        Route::post('/update', [DailySaleController::class, 'update']);
        Route::get('/statistics', [StatisticsController::class, 'dailySales']);
        Route::post('/csv', [CSVController::class, 'insertDailySaleTransactions']);
    });

    Route::prefix('consignments')->group(function () {
        Route::get('/', [ConsignmentController::class, 'index']);
        Route::get('/books', [ConsignmentController::class, 'books']);
        Route::post('/', [ConsignmentController::class, 'settle']);
        Route::get('/returns', [ConsignmentReturnController::class, 'index']);
        Route::post('/return', [ConsignmentController::class, 'return']);
        Route::get('/history', [ConsignmentController::class, 'history']);
    });

    Route::prefix('mailing-list')->group(function () {
        Route::get('/', [MailingListController::class, 'index']);
        Route::get('/statistics', [StatisticsController::class, 'mailingList']);
        Route::post('/delete', [MailingListController::class, 'delete']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/read', [NotificationController::class, 'read']);
        Route::post('/clear-all', [NotificationController::class, 'clearAll']);
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'post']);
    });

    Route::prefix('images')->group(function () {
        Route::get('/', [ImageController::class, 'index']);
        Route::post('/', [ImageController::class, 'upload']);
    });

    Route::prefix('stores')->group(function () {
        Route::get('/', [StoreController::class, 'index']);
        Route::get('/history', [StoreController::class, 'history']);
        Route::get('/balance', [StoreTransferController::class, 'balance']);
        Route::get('/book', [StoreTransferController::class, 'book']);
        Route::post('/', [StoreController::class, 'post']);
        Route::post('/transfer', [StoreTransferController::class, 'create']);
        Route::get('/list', [StoreController::class, 'list']);
        Route::get('/statistics', [StatisticsController::class, 'store']);
    });

    Route::prefix('statistics')->group(function () {
        Route::get('/sales', [StatisticsController::class, 'sales']);
        Route::get('/sales/consignments', [StatisticsController::class, 'consignmentSales']);
        Route::get('/purchases', [StatisticsController::class, 'purchases']);
    });

    Route::prefix('reminders')->group(function () {
        Route::post('/', [ReminderController::class, 'post']);
    });

    Route::prefix('google')->group(function () {
        Route::get('/login/url', [GoogleAuthController::class, 'getAuthURL']);
        Route::get('/drive/files', [GoogleDocsController::class, 'getFiles']);
    });

});

Route::post('/mailing-list', [MailingListController::class, 'post']);
