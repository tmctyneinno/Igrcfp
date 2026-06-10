<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TranslationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Translation route
Route::post('/translate', [TranslationController::class, 'translate']);

// Test route
Route::get('/test-translate', function () {
    try {
        $response = Http::timeout(10)->get('https://translate.googleapis.com/translate_a/single', [
            'client' => 'gtx',
            'sl' => 'en',
            'tl' => 'yo',
            'dt' => 't',
            'q' => 'Hello, welcome to our website'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $translated = '';
            if (!empty($data[0]) && is_array($data[0])) {
                foreach ($data[0] as $part) {
                    if (!empty($part[0])) $translated .= $part[0];
                }
            }
            return response()->json([
                'success' => true,
                'original' => 'Hello, welcome to our website',
                'translated' => $translated
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Translation failed'], 500);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});