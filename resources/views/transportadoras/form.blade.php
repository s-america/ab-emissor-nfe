@extends('layouts.app')

@section('title', 'Transportadora | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        <section class="panel">
            <div class="toolbar">
                <div><h1>{{ $transportadora->exists ? 'Editar transportadora' : 'Nova transportadora' }}</h1><p class="muted">{{ $empresa->razao_social }}</p></div>
                <a class="button button-muted" href="{{ route('transportadoras.index') }}">Voltar</a>
            </div>
            <form method="POST" action="{{ $transportadora->exists ? route('transportadoras.update', $transportadora) : route('transportadoras.store') }}">
                @csrf
                @if ($transportadora->exists) @method('PUT') @endif
                <div class="form-grid">
                    <div class="field full"><label for="nome_razao_social">Nome/Razão social</label><input id="nome_razao_social" name="nome_razao_social" value="{{ old('nome_razao_social', $transportadora->nome_razao_social) }}" required>@error('nome_razao_social') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="cpf_cnpj">CPF/CNPJ</label><input id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $transportadora->cpf_cnpj) }}" required>@error('cpf_cnpj') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="inscricao_estadual">Inscrição estadual</label><input id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $transportadora->inscricao_estadual) }}"></div>
                    <div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" value="{{ old('email', $transportadora->email) }}">@error('email') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="telefone">Telefone</label><input id="telefone" name="telefone" value="{{ old('telefone', $transportadora->telefone) }}"></div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $transportadora->ativo ?? true))>Ativa</label>
                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
