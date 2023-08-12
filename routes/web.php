<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::post('/store_file', [FileController::class, 'store_file'])->name('store_file');
Route::post('/share_file',[FileController::class,'share_file'])->name('share_file');
Route::get('/share/{receiveKey}',[FileController::class,'share'])->name('share');


//Must check app/console/commands,there is a scheduler named UpdateStatusCommand to upadate expire files and delete automatically


