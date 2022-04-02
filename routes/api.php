<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\DashboardController; 
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
Route::get('login/check', [UserController::class, 'logincheck']);


Route::group(['middleware' => ['jwt.verify:petugas,admin']], function ()
{
    Route::post('tambah', [UserController::class, 'store'])->name('store');
    Route::put('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('delete');
    Route::get('getall', [UserController::class, 'getall']);
    Route::get('show/{id}', [UserController::class, 'show']);
    Route::get('logincheck', [UserController::class, 'logincheck']);
    Route::resource('barang', BarangController::class);
    Route::put('history/pemenang/{id_history}', 'HistoryController@status');
    Route::post('reportlelang', 'LelangController@reportlelang');
    Route::post('reporthistory', 'HistoryController@reporthistory');
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dash', [DashboardController::class, 'history']);
});
Route::group(['middleware' => ['jwt.verify:pengguna']], function ()
{
    Route::post('penawaran', 'HistoryController@store');
    Route::get('showpenawaran', 'HistoryController@show2');
    Route::get('showpenawaranlelang/{id_lelang}', 'HistoryController@show3');
    Route::get('tambahpenawaran/{id_lelang}', 'HistoryController@show4');
});

Route::group(['middleware' => ['jwt.verify:pengguna,admin,petugas']], function ()
{
    Route::resource('lelang', LelangController::class);
    Route::resource('history', HistoryController::class);
    Route::post('logout', [UserController::class, 'logout']);
});


