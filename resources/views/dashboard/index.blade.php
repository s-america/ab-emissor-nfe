@extends('layouts.app')

@section('title', 'Dashboard | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')

    <main class="shell">
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <section class="panel">
            <h1>Dashboard</h1>
            <p class="muted">Usuario autenticado: {{ $usuario->nome }}</p>
            <p class="muted">Empresa: {{ $cliente->nome }}</p>

            <div class="grid">
                <div class="metric">
                    <span class="muted">Empresa ativa</span>
                    <strong style="font-size: 18px;">{{ $empresa?->razao_social ?? 'Nao cadastrada' }}</strong>
                </div>
                <div class="metric">
                    <span class="muted">Destinatarios</span>
                    <strong>{{ $totalDestinatarios }}</strong>
                </div>
                <div class="metric">
                    <span class="muted">Produtos</span>
                    <strong>{{ $totalProdutos }}</strong>
                </div>
                <div class="metric">
                    <span class="muted">Fase atual</span>
                    <strong>2.2</strong>
                </div>
            </div>
        </section>
    </main>
@endsection
