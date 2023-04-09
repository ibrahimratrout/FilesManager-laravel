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
Route::post('/logout',[APIAuthControllers::class,'logout']);


Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {

    Route::post('/register-employee', [APIAuthControllers::class, 'registerEmployee']);
    Route::delete('/admin/delete-file/{id}', [APIFileController::class, 'deleteFile']);
    Route::delete('/admin/delete-user/{id}', [APIReportController::class, 'deleteUser']);
    Route::get('/report-count-file', [APIReportController::class, 'countFile']);
    Route::get('/report-count-user', [APIReportController::class, 'countUser']);
    Route::get('/admin/report-file', [APIReportController::class, 'reportFileUser']);
    Route::get('/report-user', [APIReportController::class, 'reportUser']);
    Route::get('/admin/get-file', [APIFileController::class, 'getFile']);
    Route::post('/admin/import-file',[APIFileController::class,'import']);
    Route::put('/admin/update-file/{id}', [APIFileController::class, 'updateFile']);
    Route::get('/admin/export-file/{id}', [APIFileController::class, 'exportFile']);
    


    
});

Route::middleware(['auth:sanctum', StaffMiddleware::class])->group(function () {
    Route::get('/report-file', [APIReportController::class, 'reportFileUser']);

    Route::get('/get-file', [APIFileController::class, 'getFile']);
    Route::post('/import-file',[APIFileController::class,'import']);
    Route::put('/update-file/{id}', [APIFileController::class, 'updateFile']);
    Route::get('/export-file/{id}', [APIFileController::class, 'exportFile']);


});


