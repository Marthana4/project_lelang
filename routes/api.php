<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 
// use App\Http\Controllers\LelangController; 
// use App\Http\Controllers\PenggunaController; 
// use App\Http\Controllers\HistoryController; 
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*CRUD BARANG*/
// Route::get('/barang', 'BarangController@index');
// Route::post('/tambahbarang', 'BarangController@store');

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::put('edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
Route::delete('delete/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('delete');

Route::group(['middleware' => ['jwt.verify:petugas,admin']], function ()
{
    Route::get('getall', [UserController::class, 'getall']);
    Route::get('show/{id}', [UserController::class, 'show']);
    Route::get('logincheck', [UserController::class, 'logincheck']);
    Route::resource('barang', BarangController::class);
    Route::resource('lelang', LelangController::class);
    Route::resource('history', HistoryController::class);
    Route::put('history/pemenang/{id_history}', 'HistoryController@status');
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('reportlelang', [LelangController::class, 'reportlelang']);
    Route::post('reporthistory', 'HistoryController@reporthistory');
});
Route::group(['middleware' => ['jwt.verify:pengguna']], function ()
{
    Route::post('penawaran/{id_lelang}', [LelangController::class, 'penawaran']);
    Route::get('showpenawaran/{id}', [HistoryController::class, 'show']);
});
