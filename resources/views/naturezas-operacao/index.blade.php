@extends('layouts.app')

@section('title', 'Naturezas de operação | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar">
                <div><h1>Naturezas de operação</h1><p class="muted">{{ $empresa->razao_social }}</p></div>
                <a class="button" href="{{ route('naturezas-operacao.create') }}">Nova natureza</a>
            </div>
            <form method="GET" action="{{ route('naturezas-operacao.index') }}" class="toolbar">
                <input name="busca" value="{{ request('busca') }}" placeholder="Buscar por descrição">
                <button class="button button-muted" type="submit">Buscar</button>
            </form>
            <table class="table">
                <thead><tr><th>Descrição</th><th>Tipo</th><th>CFOP padrão</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($naturezasOperacao as $naturezaOperacao)
                        <tr>
                            <td>{{ $naturezaOperacao->descricao }}</td><td>{{ ucfirst($naturezaOperacao->tipo_operacao) }}</td>
                            <td>{{ $naturezaOperacao->cfop_padrao }}</td><td>{{ $naturezaOperacao->ativo ? 'Ativa' : 'Inativa' }}</td>
                            <td><a href="{{ route('naturezas-operacao.edit', $naturezaOperacao) }}">Editar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Nenhuma natureza de operação cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $naturezasOperacao->links() }}
        </section>
    </main>
@endsection
