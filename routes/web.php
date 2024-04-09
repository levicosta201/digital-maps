<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Api Digital Maps',
        'version' => '0.1'
    ]);
});
