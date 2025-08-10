@extends('layouts.app')

@section('title', 'Minhas buscas')
@section('content')
    <h2 style="margin: 12px 0 16px;">Minhas buscas</h2>

    @if($list->count())
        <div class="card" style="overflow:auto;">
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:8px;">Termo</th>
                        <th style="text-align:left; padding:8px;">Resultados</th>
                        <th style="text-align:left; padding:8px;">Quando</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $row)
                        <tr style="border-top:1px solid rgba(148,163,184,0.15);">
                            <td style="padding:8px;">
                                <a href="{{ route('search.index', ['q' => $row->term]) }}">{{ $row->term }}</a>
                            </td>
                            <td style="padding:8px;">{{ $row->total_results ?? '—' }}</td>
                            <td style="padding:8px;">{{ $row->created_at->tz('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:14px;">
            {!! $list->links() !!}
        </div>
    @else
        <div class="empty">Você ainda não fez buscas.</div>
    @endif
@endsection
