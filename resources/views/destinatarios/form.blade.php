@extends('layouts.app')

@section('title', 'Destinatario | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')

    <main class="shell">
        <section class="panel">
            <div class="toolbar">
                <div>
                    <h1>{{ $destinatario->exists ? 'Editar destinatario' : 'Novo destinatario' }}</h1>
                    <p class="muted">{{ $empresa->razao_social }}</p>
                </div>
                <a class="button button-muted" href="{{ route('destinatarios.index') }}">Voltar</a>
            </div>

            <form method="POST" action="{{ $destinatario->exists ? route('destinatarios.update', $destinatario) : route('destinatarios.store') }}">
                @csrf
                @if ($destinatario->exists)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div class="field full">
                        <label for="nome_razao_social">Nome/Razao social</label>
                        <input id="nome_razao_social" name="nome_razao_social" value="{{ old('nome_razao_social', $destinatario->nome_razao_social) }}" required>
                        @error('nome_razao_social') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="cpf_cnpj">CPF/CNPJ</label>
                        <input id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $destinatario->cpf_cnpj) }}" required>
                        @error('cpf_cnpj') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="indicador_ie">Indicador IE</label>
                        <select id="indicador_ie" name="indicador_ie">
                            @foreach (['contribuinte' => 'Contribuinte', 'isento' => 'Isento', 'nao_contribuinte' => 'Nao contribuinte'] as $valor => $label)
                                <option value="{{ $valor }}" @selected(old('indicador_ie', $destinatario->indicador_ie) === $valor)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('indicador_ie') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="inscricao_estadual">Inscricao estadual</label>
                        <input id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $destinatario->inscricao_estadual) }}">
                    </div>

                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $destinatario->email) }}">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="telefone">Telefone</label>
                        <input id="telefone" name="telefone" value="{{ old('telefone', $destinatario->telefone) }}">
                    </div>

                    <div class="field full">
                        <label for="logradouro">Logradouro</label>
                        <input id="logradouro" name="logradouro" value="{{ old('logradouro', $destinatario->logradouro) }}">
                    </div>

                    <div class="field">
                        <label for="numero">Numero</label>
                        <input id="numero" name="numero" value="{{ old('numero', $destinatario->numero) }}">
                    </div>

                    <div class="field">
                        <label for="bairro">Bairro</label>
                        <input id="bairro" name="bairro" value="{{ old('bairro', $destinatario->bairro) }}">
                    </div>

                    <div class="field">
                        <label for="municipio">Municipio</label>
                        <input id="municipio" name="municipio" value="{{ old('municipio', $destinatario->municipio) }}">
                    </div>

                    <div class="field">
                        <label for="codigo_municipio_ibge">Codigo IBGE</label>
                        <input id="codigo_municipio_ibge" name="codigo_municipio_ibge" value="{{ old('codigo_municipio_ibge', $destinatario->codigo_municipio_ibge) }}">
                        @error('codigo_municipio_ibge') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="uf">UF</label>
                        <input id="uf" name="uf" maxlength="2" value="{{ old('uf', $destinatario->uf) }}">
                    </div>

                    <div class="field">
                        <label for="cep">CEP</label>
                        <input id="cep" name="cep" value="{{ old('cep', $destinatario->cep) }}">
                        @error('cep') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <label class="check">
                    <input name="ativo" type="checkbox" value="1" @checked(old('ativo', $destinatario->ativo ?? true))>
                    Ativo
                </label>

                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
