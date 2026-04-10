<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class JapaneseDictionaryController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $query = trim($validated['query']);

        $suggestions = Cache::remember(
            'japanese_dictionary_suggestions_' . md5($query),
            now()->addHours(12),
            function () use ($query) {
                $response = Http::timeout(10)
                    ->acceptJson()
                    ->get('https://jisho.org/api/v1/search/words', [
                        'keyword' => $query,
                    ]);

                if (! $response->successful()) {
                    return [];
                }

                $payload = $response->json('data', []);
                $suggestions = [];

                foreach ($payload as $entry) {
                    foreach ($entry['japanese'] ?? [] as $japaneseEntry) {
                        $reading = $japaneseEntry['reading'] ?? '';
                        $word = $japaneseEntry['word'] ?? null;

                        if (! $word || $reading !== $query) {
                            continue;
                        }

                        $suggestions[] = $word;
                    }
                }

                return array_values(array_unique(array_slice($suggestions, 0, 10)));
            }
        );

        return response()->json([
            'query' => $query,
            'suggestions' => $suggestions,
        ]);
    }
}
