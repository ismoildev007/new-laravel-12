<?php

use App\Helpers\Roles;
use Illuminate\Support\Facades\Route;
use Modules\FileManager\App\Http\Controllers\FileManagerController;

Route::prefix('v1/files')->group(function () {
    Route::post('/upload', [FileManagerController::class, 'upload']);
});

Route::prefix('v1/admin/files')->middleware('auth:api')->group(function () {
    Route::post('/upload', [FileManagerController::class, 'adminUpload']);
    Route::delete('{file}', [FileManagerController::class, 'delete'])->whereNumber('file');
    Route::get('/{file}', [FileManagerController::class, 'show'])->whereNumber('file');
});
