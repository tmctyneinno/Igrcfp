<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationController extends Controller
{
    public function translate(Request $request)
{
    $validated = $request->validate([
        'texts'  => 'required|array|max:100',
        'texts.*'=> 'required|string|max:800',
        'target' => 'required|string|min:2|max:10',
        'source' => 'nullable|string|min:2|max:10',
    ]);

    $delimiter = ' ||| ';
    $joined = implode($delimiter, $validated['texts']);

    // If joined text is too long, translate individually with fallback
    if (strlen($joined) > 4500) {
        return $this->translateIndividually($validated);
    }

    try {
        $translated = $this->callGoogleTranslate(
            $joined,
            $validated['target'],
            $validated['source'] ?? 'auto'
        );

        if ($translated === null) {
            return $this->translateIndividually($validated);
        }

        // Split result back by delimiter
        $parts = array_map('trim', explode('|||', $translated));

        // If split count doesn't match, fall back to individual
        if (count($parts) !== count($validated['texts'])) {
            return $this->translateIndividually($validated);
        }

        return response()->json(['success' => true, 'translated' => $parts]);

    } catch (\Exception $e) {
        Log::error('Translation batch error: ' . $e->getMessage());
        return $this->translateIndividually($validated);
    }
}

private function callGoogleTranslate(string $text, string $target, string $source = 'auto'): ?string
{
    try {
        $response = Http::timeout(15)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
            ->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => $source,
                'tl'     => $target,
                'dt'     => 't',
                'q'      => $text,
            ]);

        if (!$response->successful()) {
            Log::warning('Google Translate HTTP error: ' . $response->status());
            return null;
        }

        $data = $response->json();
        $result = '';

        if (!empty($data[0]) && is_array($data[0])) {
            foreach ($data[0] as $item) {
                if (!empty($item[0])) $result .= $item[0];
            }
        }

        return $result ?: null;

    } catch (\Exception $e) {
        Log::error('Google Translate call failed: ' . $e->getMessage());
        return null;
    }
}

private function translateIndividually(array $validated): \Illuminate\Http\JsonResponse
{
    $results = [];

    foreach ($validated['texts'] as $text) {
        $translated = $this->callGoogleTranslate(
            $text,
            $validated['target'],
            $validated['source'] ?? 'auto'
        );
        $results[] = $translated ?? $text; // fallback to original if failed
    }

    return response()->json(['success' => true, 'translated' => $results]);
}
}