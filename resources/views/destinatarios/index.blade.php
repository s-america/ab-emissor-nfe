@extends('layouts.app')

@section('title', 'Destinatarios | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')

    <main class="shell">
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <section class="panel">
            <div class="toolbar">
                <div>
                    <h1>Destinatarios</h1>
                    <p class="muted">{{ $empresa->razao_social }}</p>
                </div>
                <a class="button" href="{{ route('destinatarios.create') }}">Novo destinatario</a>
            </div>

            <form method="GET" action="{{ route('destinatarios.index') }}" class="toolbar">
                <input name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome ou CPF/CNPJ">
                <button class="button button-muted" type="submit">Buscar</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Nome/Razao social</th>
                        <th>CPF/CNPJ</th>
                        <th>UF</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinatarios as $destinatario)
                        <tr>
                            <td>{{ $destinatario->nome_razao_social }}</td>
                            <td>{{ $destinatario->cpf_cnpj }}</td>
                            <td>{{ $destinatario->uf }}</td>
                            <td>{{ $destinatario->ativo ? 'Ativo' : 'Inativo' }}</td>
                            <td><a href="{{ route('destinatarios.edit', $destinatario) }}">Editar</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">Nenhum destinatario cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $destinatarios->links() }}
        </section>
    </main>
@endsection
