<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsApiService
{
    public function search(string $q, int $page = 1, int $pageSize = 9): array
    {
        $base = config('services.newsapi.base_url');
        $key  = config('services.newsapi.key');

        $resp = Http::timeout(10)->get("$base/everything", [
            'q'        => $q,
            'sortBy'   => 'publishedAt',
            'language' => 'pt',     // ajuste se quiser
            'page'     => $page,
            'pageSize' => $pageSize,
            'apiKey'   => $key,
        ]);

        if (!$resp->ok()) {
            throw new \RuntimeException('Falha ao consultar a NewsAPI: '.$resp->status());
        }

        return $resp->json(); // ['status','totalResults','articles'=>[]]
    }
}
