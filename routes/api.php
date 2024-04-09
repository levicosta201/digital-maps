<?php

use App\src\Infrastructure\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;

Route::post('/points', [PointController::class, 'store']);
Route::get('/points', [PointController::class, 'list']);
