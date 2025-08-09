<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// Serve React from Laravel
Route::get('/{any}', function () {
    return File::get(public_path('index.html'));
})->where('any', '.*');

