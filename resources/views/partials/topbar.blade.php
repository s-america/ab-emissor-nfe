<header class="topbar">
    <div class="brand">AB Emissor NF-e</div>
    <nav class="nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('destinatarios.index') }}">Destinatarios</a>
        <a href="{{ route('produtos.index') }}">Produtos</a>
        <a href="{{ route('transportadoras.index') }}">Transportadoras</a>
        <a href="{{ route('naturezas-operacao.index') }}">Naturezas</a>
        <a href="{{ route('cfops.index') }}">CFOP</a>
        <a href="{{ route('ncms.index') }}">NCM</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="button button-muted" type="submit">Sair</button>
        </form>
    </nav>
</header>
