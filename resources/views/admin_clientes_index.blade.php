@extends('layouts.app')

@section('title', 'Empresas | Administração')

@section('body')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar">
                <div><h1>Empresas</h1><p class="muted">Painel administrativo Salta Digital</p></div>
                <a class="button" href="{{ route('admin.empresas.create') }}">Nova empresa</a>
            </div>
            <table class="table">
                <thead><tr><th>Empresa</th><th>Identificador</th><th>Emitentes</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nome }}</td><td>{{ $cliente->slug }}</td><td>{{ $cliente->empresas_count }}</td>
                            <td>{{ $cliente->ativo ? 'Ativo' : 'Desabilitado' }}</td>
                            <td><a href="{{ route('admin.empresas.edit', $cliente) }}">Editar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Nenhuma empresa cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $clientes->links() }}
        </section>
    </main>
@endsection
