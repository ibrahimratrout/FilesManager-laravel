<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController\APIAuthControllers;
use App\Http\Controllers\APIController\APIFileController;
use App\Http\Controllers\APIController\APIReportController;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StaffMiddleware;


Route::post('/login',[APIAuthControllers::class,'login']);

Route::post('/register',[APIAuthControllers::class,'registerManager']);


Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {

    Route::post('/register-employee', [APIAuthControllers::class, 'registerEmployee']);
    Route::delete('/delete-file/{id}', [APIFileController::class, 'deleteFile']);
    Route::get('/report-count-file', [APIReportController::class, 'countFile']);
    Route::get('/report-count-user', [APIReportController::class, 'countUser']);
    Route::get('/report-file', [APIReportController::class, 'reportFileUser']);
    Route::get('/report-user', [APIReportController::class, 'reportUser']);
    
});

Route::get('/get-file', [APIFileController::class, 'getFile']);
Route::post('/import-file',[APIFileController::class,'import']);
Route::put('/update-file/{id}', [APIFileController::class, 'updateFile']);
Route::get('/export-file/{id}', [APIFileController::class, 'exportFile']);


Route::middleware(['auth:sanctum', StaffMiddleware::class])->group(function () {



});


