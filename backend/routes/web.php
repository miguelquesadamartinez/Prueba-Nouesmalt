<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Biblioteca API',
        'version' => '1.0.0',
    ]);
});
