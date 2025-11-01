<?php

use App\Http\Controllers\API\LoketController;
use App\Http\Controllers\API\AntrianController;

Route::apiResource('lokets', LoketController::class);

// Antrian endpoints
Route::post('lokets/{loket}/antrians', [AntrianController::class,'createForLoket']);
Route::get('lokets/{loket}/waiting', [AntrianController::class,'waitingForLoket']);
Route::patch('antrians/{antrian}/status', [AntrianController::class,'updateStatus']);
Route::get('antrians/current', [AntrianController::class,'currentCalled']);
