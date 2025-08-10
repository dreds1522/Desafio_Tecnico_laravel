@extends('layouts.app')

@section('title', 'Início · NewsAPI Blog')
@section('meta_description', 'Busque títulos de notícias e explore artigos em formato de blog.')

@section('content')
<section class="hero">
    <h1>Encontre notícias rapidamente</h1>
    <p>Digite um título ou palavra-chave e veja os artigos mais recentes.</p>

    <form class="search" method="GET" action="{{ route('search.index') }}">
        <input
            type="text"
            name="q"
            placeholder="Ex.: Tesla, IA, eleições..."
            value="{{ old('q', $q ?? '') }}"
            aria-label="Buscar notícias por título" />
        <button class="btn" type="submit">Buscar</button>
    </form>
</section>

@if(!empty($error))
<div class="empty" role="alert" style="margin-top:16px;">
    {{ $error }}
</div>
@endif

<section aria-label="Resultados">
    @if(!empty($paginator) && $paginator->count())
    <div class="grid">
        @foreach($paginator as $a)
        <article class="card">
            <h3>
                @if(!empty($a['url']))
                <a href="{{ $a['url'] }}" target="_blank" rel="noopener">{{ $a['title'] ?? 'Sem título' }}</a>
                @else
                {{ $a['title'] ?? 'Sem título' }}
                @endif
            </h3>
            <div class="meta">
                {{ data_get($a, 'source.name', 'Fonte desconhecida') }} •
                {{ isset($a['publishedAt']) ? \Illuminate\Support\Carbon::parse($a['publishedAt'])->tz('America/Sao_Paulo')->format('d/m/Y H:i') : 'Data não informada' }}
            </div>
            <p>{{ $a['description'] ?? 'Sem descrição disponível.' }}</p>
        </article>
        @endforeach
    </div>

    <div class="mt-4 pager">
        {!! $paginator->withQueryString()->onEachSide(1)->links() !!}
    </div>

    @elseif(($q ?? '') !== '' && empty($error))
    <div class="empty" role="status">Nenhum resultado para “{{ $q }}”.</div>
    @else
    <div class="empty" role="status">Faça uma busca acima para começar.</div>
    @endif
</section>
@endsection

