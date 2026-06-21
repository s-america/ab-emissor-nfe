@extends('layouts.app')

@section('title', 'Dashboard | AB Emissor NF-e')

@section('body')
    <header class="topbar">
        <div class="brand">AB Emissor NF-e</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button button-muted" type="submit">Sair</button>
        </form>
    </header>

    <main class="shell">
        <section class="panel">
            <h1>Dashboard</h1>
            <p class="muted">Usuario autenticado: {{ $usuario->nome }}</p>

            <div class="grid">
                <div class="metric">
                    <span class="muted">Tenants</span>
                    <strong>{{ $totalTenants }}</strong>
                </div>
                <div class="metric">
                    <span class="muted">Empresas</span>
                    <strong>{{ $totalEmpresas }}</strong>
                </div>
                <div class="metric">
                    <span class="muted">Fase atual</span>
                    <strong>1</strong>
                </div>
            </div>
        </section>

        <section class="panel" style="margin-top: 16px;">
            <h2>Tenants vinculados</h2>

            <ul class="list">
                @forelse ($tenantsUsuario as $tenant)
                    <li>
                        <span>{{ $tenant->nome }}</span>
                        <span class="muted">{{ $tenant->pivot->perfil }}</span>
                    </li>
                @empty
                    <li class="muted">Nenhum tenant vinculado ao usuario atual.</li>
                @endforelse
            </ul>
        </section>
    </main>
@endsection
