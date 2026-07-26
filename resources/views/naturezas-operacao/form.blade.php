@extends('layouts.app')

@section('title', 'Natureza de operação | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        <section class="panel">
            <div class="toolbar">
                <div><h1>{{ $naturezaOperacao->exists ? 'Editar natureza de operação' : 'Nova natureza de operação' }}</h1><p class="muted">{{ $empresa->razao_social }}</p></div>
                <a class="button button-muted" href="{{ route('naturezas-operacao.index') }}">Voltar</a>
            </div>
            <form method="POST" action="{{ $naturezaOperacao->exists ? route('naturezas-operacao.update', $naturezaOperacao) : route('naturezas-operacao.store') }}">
                @csrf
                @if ($naturezaOperacao->exists) @method('PUT') @endif
                <div class="form-grid">
                    <div class="field full"><label for="descricao">Descrição</label><input id="descricao" name="descricao" value="{{ old('descricao', $naturezaOperacao->descricao) }}" required>@error('descricao') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="tipo_operacao">Tipo de operação</label><select id="tipo_operacao" name="tipo_operacao"><option value="saida" @selected(old('tipo_operacao', $naturezaOperacao->tipo_operacao) === 'saida')>Saída</option><option value="entrada" @selected(old('tipo_operacao', $naturezaOperacao->tipo_operacao) === 'entrada')>Entrada</option></select>@error('tipo_operacao') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field">
                        <label for="cfop_padrao">CFOP padrão ativo</label>
                        <select id="cfop_padrao" name="cfop_padrao">
                            <option value="">Selecione</option>
                            @foreach ($cfops as $cfop)
                                <option value="{{ $cfop->codigo }}" @selected(old('cfop_padrao', $naturezaOperacao->cfop_padrao) === $cfop->codigo)>{{ $cfop->codigo }} - {{ $cfop->descricao }}</option>
                            @endforeach
                        </select>
                        @error('cfop_padrao') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $naturezaOperacao->ativo ?? true))>Ativa</label>
                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
