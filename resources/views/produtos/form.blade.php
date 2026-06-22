@extends('layouts.app')

@section('title', 'Produto | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')

    <main class="shell">
        <section class="panel">
            <div class="toolbar">
                <div>
                    <h1>{{ $produto->exists ? 'Editar produto' : 'Novo produto' }}</h1>
                    <p class="muted">{{ $empresa->razao_social }}</p>
                </div>
                <a class="button button-muted" href="{{ route('produtos.index') }}">Voltar</a>
            </div>

            <form method="POST" action="{{ $produto->exists ? route('produtos.update', $produto) : route('produtos.store') }}">
                @csrf
                @if ($produto->exists)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="codigo">Codigo interno</label>
                        <input id="codigo" name="codigo" value="{{ old('codigo', $produto->codigo) }}">
                    </div>

                    <div class="field">
                        <label for="unidade_comercial">Unidade</label>
                        <input id="unidade_comercial" name="unidade_comercial" value="{{ old('unidade_comercial', $produto->unidade_comercial ?? 'UN') }}" required>
                        @error('unidade_comercial') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field full">
                        <label for="descricao">Descricao</label>
                        <input id="descricao" name="descricao" value="{{ old('descricao', $produto->descricao) }}" required>
                        @error('descricao') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="ncm">NCM</label>
                        <input id="ncm" name="ncm" value="{{ old('ncm', $produto->ncm) }}">
                        @error('ncm') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="cest">CEST</label>
                        <input id="cest" name="cest" value="{{ old('cest', $produto->cest) }}">
                        @error('cest') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="cfop_padrao">CFOP padrao</label>
                        <input id="cfop_padrao" name="cfop_padrao" value="{{ old('cfop_padrao', $produto->cfop_padrao) }}">
                        @error('cfop_padrao') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="origem">Origem</label>
                        <select id="origem" name="origem">
                            @foreach (['0' => '0 - Nacional', '1' => '1 - Estrangeira importacao direta', '2' => '2 - Estrangeira mercado interno'] as $valor => $label)
                                <option value="{{ $valor }}" @selected(old('origem', $produto->origem ?? '0') === $valor)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="cst_csosn">CST/CSOSN</label>
                        <input id="cst_csosn" name="cst_csosn" value="{{ old('cst_csosn', $produto->cst_csosn) }}">
                    </div>

                    <div class="field">
                        <label for="cst_pis">CST PIS</label>
                        <input id="cst_pis" name="cst_pis" value="{{ old('cst_pis', $produto->cst_pis) }}">
                    </div>

                    <div class="field">
                        <label for="cst_cofins">CST COFINS</label>
                        <input id="cst_cofins" name="cst_cofins" value="{{ old('cst_cofins', $produto->cst_cofins) }}">
                    </div>

                    <div class="field">
                        <label for="valor_unitario">Valor unitario</label>
                        <input id="valor_unitario" name="valor_unitario" value="{{ old('valor_unitario', $produto->valor_unitario ?? 0) }}" required>
                        @error('valor_unitario') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <label class="check">
                    <input name="ativo" type="checkbox" value="1" @checked(old('ativo', $produto->ativo ?? true))>
                    Ativo
                </label>

                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
