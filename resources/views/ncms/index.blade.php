@extends('layouts.app')

@section('title', 'NCM | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar"><div><h1>NCM</h1><p class="muted">Catálogo fiscal compartilhado</p></div>@if ($podeAdministrar)<a class="button" href="{{ route('ncms.create') }}">Novo NCM</a>@endif</div>
            <form method="GET" action="{{ route('ncms.index') }}" class="toolbar"><input name="busca" value="{{ request('busca') }}" placeholder="Buscar por codigo ou descricao"><button class="button button-muted" type="submit">Buscar</button></form>
            <table class="table">
                <thead><tr><th>Código</th><th>Descrição</th><th>Vigência</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($ncms as $ncm)
                        <tr><td>{{ $ncm->codigo }}</td><td>{{ $ncm->descricao }}</td><td>{{ $ncm->vigente_de?->format('d/m/Y') }} @if($ncm->vigente_ate) a {{ $ncm->vigente_ate->format('d/m/Y') }} @endif</td><td>{{ $ncm->ativo ? 'Ativo' : 'Inativo' }}</td><td>@if ($podeAdministrar)<a href="{{ route('ncms.edit', $ncm) }}">Editar</a>@endif</td></tr>
                    @empty <tr><td colspan="5" class="muted">Nenhum NCM cadastrado.</td></tr> @endforelse
                </tbody>
            </table>
            {{ $ncms->links() }}
        </section>
    </main>
@endsection
