<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Services\NewsApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    public function index(Request $request, NewsApiService $news)
    {
        $q     = trim((string)$request->query('q', ''));
        $page  = max(1, (int)$request->query('page', 1));
        $per   = 9;

        $articles = [];
        $total    = 0;
        $paginator = null;
        $error    = null;

        if ($q !== '') {
            try {
                $data      = $news->search($q, $page, $per);
                $articles  = $data['articles'] ?? [];
                $total     = (int)($data['totalResults'] ?? 0);

                // salva a busca (não duplica por página)
                if ($page === 1) {
                    Search::create([
                        'term'          => $q,
                        'total_results' => $total,
                        'ip'            => $request->ip(),
                        'user_agent'    => substr((string)$request->userAgent(), 0, 255),
                    ]);
                }

                // paginação baseada no total da API
                $paginator = new LengthAwarePaginator(
                    $articles, $total, $per, $page,
                    ['path' => route('search.index'), 'query' => ['q' => $q]]
                );
            } catch (\Throwable $e) {
                $error = 'Não foi possível buscar agora. Tente novamente em instantes.';
            }
        }

        return view('home', [
            'q'         => $q,
            'articles'  => $articles,
            'paginator' => $paginator,
            'error'     => $error,
        ]);
    }
public function searches()

 {

$list = Search::query()->latest()->paginate(12);

return view('searches.index', compact('list'));

 }
    
}
