<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('lots', LotController::class);
    Route::resource('constructions', ConstructionController::class);
    Route::resource('providers', ProviderController::class);
    Route::resource('materials', MaterialController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('logs', LogController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('updates', [UpdateController::class, 'index']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate_report'])->name('reports.generate');

    Route::get('/getUser', [AjaxController::class, 'getUser'])->name('getUser');
    Route::get('/delUser', [AjaxController::class, 'delUser'])->name('delUser');

    Route::get('/getLot', [AjaxController::class, 'getLot'])->name('getLot');
    Route::get('/delLot', [AjaxController::class, 'delLot'])->name('delLot');

    Route::get('/getConstruction', [AjaxController::class, 'getConstruction'])->name('getConstruction');
    Route::get('/delConstruction', [AjaxController::class, 'delConstruction'])->name('delConstruction');

    Route::get('/getProvider', [AjaxController::class, 'getProvider'])->name('getProvider');
    Route::get('/delProvider', [AjaxController::class, 'delProvider'])->name('delProvider');

    Route::get('/getMaterial', [AjaxController::class, 'getMaterial'])->name('getMaterial');
    Route::get('/delMaterial', [AjaxController::class, 'delMaterial'])->name('delMaterial');

    Route::get('/getInvoice', [AjaxController::class, 'getInvoice'])->name('getInvoice');
    Route::get('/delInvoice', [AjaxController::class, 'delInvoice'])->name('delInvoice');

    Route::get('/getData', [AjaxController::class, 'getData'])->name('getData');
});
