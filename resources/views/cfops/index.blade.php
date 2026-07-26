@extends('layouts.app')

@section('title', 'CFOP | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar"><div><h1>CFOP</h1><p class="muted">Catálogo fiscal compartilhado</p></div>@if ($podeAdministrar)<a class="button" href="{{ route('cfops.create') }}">Novo CFOP</a>@endif</div>
            <form method="GET" action="{{ route('cfops.index') }}" class="toolbar"><input name="busca" value="{{ request('busca') }}" placeholder="Buscar por código ou descrição"><button class="button button-muted" type="submit">Buscar</button></form>
            <table class="table">
                <thead><tr><th>Código</th><th>Descrição</th><th>Tipo</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($cfops as $cfop)
                        <tr><td>{{ $cfop->codigo }}</td><td>{{ $cfop->descricao }}</td><td>{{ ucfirst($cfop->tipo_operacao) }}</td><td>{{ $cfop->ativo ? 'Ativo' : 'Inativo' }}</td><td>@if ($podeAdministrar)<a href="{{ route('cfops.edit', $cfop) }}">Editar</a>@endif</td></tr>
                    @empty <tr><td colspan="5" class="muted">Nenhum CFOP cadastrado.</td></tr> @endforelse
                </tbody>
            </table>
            {{ $cfops->links() }}
        </section>
    </main>
@endsection
