<?php

use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

// Route for uploading excel file to server
Route::post('/upload', [ExcelController::class, 'upload']);

// Route for uploading excel file to server
Route::post('/check', [ExcelController::class, 'check']);

// Route for getting parsed routes groupped by date
Route::get('/rows', [ExcelController::class, 'getRows']);
