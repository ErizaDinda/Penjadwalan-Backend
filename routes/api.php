<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\DosenController;
use App\http\Controllers\HariController;
use App\http\Controllers\JamController;
use App\http\Controllers\MatkulController;
use App\http\Controllers\RuangController;
use App\http\Controllers\UserController;
use App\http\Controllers\PengampuController;
use App\http\Controllers\JadkulController;
use App\http\Controllers\WktTdkBersediaController;
use App\http\Controllers\LoginController;
 


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');


//crud jam
Route::resource('/jam', JamController::class);
Route::resource('/dosen', DosenController::class);
Route::resource('/hari', HariController::class);
Route::resource('/matkul', MatkulController::class);
Route::resource('/ruang', RuangController::class);
Route::resource('/pengampu', PengampuController::class);  
Route::resource('/jadwalkuliah', JadkulController::class);  
Route::resource('/waktu-tdk-bersedia', WktTdkBersediaController::class); 
Route::resource('/test', Test::class);
Route::post('/penjadwalan',[Penjadwalan::class,'penjadwalan']);
Route::get('/ujicoba',[Penjadwalan::class,'uji']);