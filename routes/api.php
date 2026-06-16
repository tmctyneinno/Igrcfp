<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ResearchController;
use App\Http\Controllers\TranslationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Translation route
Route::post('/translate', [TranslationController::class, 'translate']);
 
Route::prefix('v1')->group(function () {
    // Public access for research documents
    Route::get('/research', [ResearchController::class, 'index']);
    Route::get('/research/{slug}', [ResearchController::class, 'show']);
});