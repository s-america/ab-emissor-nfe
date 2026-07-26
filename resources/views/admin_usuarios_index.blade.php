@extends('layouts.app')

@section('title', 'Usuarios administrativos | Administração')

@section('body')
    <main class="shell">
        @if (session('status')) <div class="alert">{{ session('status') }}</div> @endif
        <section class="panel">
            <div class="toolbar"><div><h1>Usuarios</h1><p class="muted">Acessos de contabilidade e Salta Digital</p></div><a class="button" href="{{ route('admin.usuarios.create') }}">Novo usuario</a></div>
            <table class="table">
                <thead><tr><th>Nome</th><th>E-mail</th><th>Papel</th><th>Empresa</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nome }}</td><td>{{ $usuario->email }}</td><td>{{ $usuario->papeis->pluck('nome')->join(', ') ?: 'Sem papel' }}</td><td>{{ $usuario->tenants->pluck('nome')->join(', ') ?: 'Salta Digital' }}</td><td>{{ $usuario->ativo ? 'Ativo' : 'Desabilitado' }}</td><td><a href="{{ route('admin.usuarios.edit', $usuario) }}">Editar</a></td>
                        </tr>
                    @empty <tr><td colspan="6" class="muted">Nenhum usuario cadastrado.</td></tr> @endforelse
                </tbody>
            </table>
            {{ $usuarios->links() }}
        </section>
    </main>
@endsection
