<?php

use Illuminate\Support\Facades\Route;
use Modules\FileManager\Http\Controllers\FileManagerController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('filemanagers', FileManagerController::class)->names('filemanager');
});
