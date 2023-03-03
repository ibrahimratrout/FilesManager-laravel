<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController\APIAuthControllers;
use App\Http\Controllers\APIController\APIFileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[APIAuthControllers::class,'register']);
Route::post('/login',[APIAuthControllers::class,'login']);


Route::post('/import-file',[APIFileController::class,'import']);

Route::get('/export-file/{id}', [APIFileController::class, 'exportFile']);

Route::post('/delete-file/{id}', [APIFileController::class, 'deleteFile']);

