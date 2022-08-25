<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TransactionController;
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
        Route::get('/categories', [BookController::class, 'categories']);
        Route::get('/statistics', [StatisticsController::class, 'books']);
        Route::get('/search', [SearchController::class, 'books']);
        Route::post('/', [BookController::class, 'post']);
        Route::post('/csv', [CSVController::class, 'insertBooks']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'post']);
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
        Route::post('/', [ConsignmentController::class, 'create']);
        Route::get('/history', [ConsignmentController::class, 'history']);
    });

});
