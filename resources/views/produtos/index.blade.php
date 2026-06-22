@extends('layouts.app')

@section('title', 'Produtos | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')

    <main class="shell">
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <section class="panel">
            <div class="toolbar">
                <div>
                    <h1>Produtos</h1>
                    <p class="muted">{{ $empresa->razao_social }}</p>
                </div>
                <a class="button" href="{{ route('produtos.create') }}">Novo produto</a>
            </div>

            <form method="GET" action="{{ route('produtos.index') }}" class="toolbar">
                <input name="busca" value="{{ request('busca') }}" placeholder="Buscar por codigo, descricao ou NCM">
                <button class="button button-muted" type="submit">Buscar</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Descricao</th>
                        <th>NCM</th>
                        <th>CFOP</th>
                        <th>Valor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produtos as $produto)
                        <tr>
                            <td>{{ $produto->codigo }}</td>
                            <td>{{ $produto->descricao }}</td>
                            <td>{{ $produto->ncm }}</td>
                            <td>{{ $produto->cfop_padrao }}</td>
                            <td>R$ {{ number_format((float) $produto->valor_unitario, 2, ',', '.') }}</td>
                            <td><a href="{{ route('produtos.edit', $produto) }}">Editar</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">Nenhum produto cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $produtos->links() }}
        </section>
    </main>
@endsection
