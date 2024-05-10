<?php


use Illuminate\Support\Facades\Route;
use Modules\Invoices\App\Http\Controllers\InvoicesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->name('invoices')->group(function () {
    Route::get('/invoices', [InvoicesController::class, 'list']);
    Route::post('/invoices', [InvoicesController::class, 'ajax']);
    Route::get('/invoices/profile/{id}/{tab?}', [InvoicesController::class, 'profile']);
    Route::post('/invoices/profile/{id}/{tab?}', [InvoicesController::class, 'ajax']);
    Route::get('/invoice/profile/new', [InvoicesController::class, 'create']);
    Route::post('/invoice/profile/new', [InvoicesController::class, 'ajax']);
});

