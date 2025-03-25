<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        throw new Exception('Try message');
    } catch (Exception $e) {
        Debugbar::addException($e);
    }
    return view('welcome');
});
