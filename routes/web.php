<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVController;
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
Route::get('/', [CSVController::class, "convertPage"]);
Route::get('/viewfiles', [CSVController::class, "getImportFiles"]);
Route::get('/startimport/{fileid}', [CSVController::class, "importData"]);
Route::post('/submit', [CSVController::class, "getCsv"]);
Route::get('/view', [CSVController::class, "viewTable"]);
