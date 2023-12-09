<?php

use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/upload/file', [UploadController::class,"uploadFile"])->name('upload.file');

Route::controller(StatisticsController::class)->group(function (){
        Route::get('/top','top')->name('top');
        Route::get('/status','status')->name('status');
        Route::get('/grup','grup')->name('grup');
        Route::get('/consonants','consonants')->name('consonants');
});
