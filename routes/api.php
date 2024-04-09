<?php

use App\src\Infrastructure\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;


Route::prefix('points')->as('points.')->group(function() {
    Route::post('', [PointController::class, 'store']);
    Route::get('', [PointController::class, 'list']);
    Route::put('{uuid}', [PointController::class, 'update']);
    Route::delete('{uuid}', [PointController::class, 'delete']);
    Route::get('/near/{latitude}/{longitude}/{distance}/{hour}', [PointController::class, 'near']);
});

