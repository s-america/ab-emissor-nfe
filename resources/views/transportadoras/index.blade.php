@extends('layouts.app')

@section('title', 'Transportadoras | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar">
                <div><h1>Transportadoras</h1><p class="muted">{{ $empresa->razao_social }}</p></div>
                <a class="button" href="{{ route('transportadoras.create') }}">Nova transportadora</a>
            </div>
            <form method="GET" action="{{ route('transportadoras.index') }}" class="toolbar">
                <input name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome ou CPF/CNPJ">
                <button class="button button-muted" type="submit">Buscar</button>
            </form>
            <table class="table">
                <thead><tr><th>Nome/Razão social</th><th>CPF/CNPJ</th><th>E-mail</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($transportadoras as $transportadora)
                        <tr>
                            <td>{{ $transportadora->nome_razao_social }}</td><td>{{ $transportadora->cpf_cnpj }}</td>
                            <td>{{ $transportadora->email }}</td><td>{{ $transportadora->ativo ? 'Ativa' : 'Inativa' }}</td>
                            <td><a href="{{ route('transportadoras.edit', $transportadora) }}">Editar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Nenhuma transportadora cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $transportadoras->links() }}
        </section>
    </main>
@endsection
