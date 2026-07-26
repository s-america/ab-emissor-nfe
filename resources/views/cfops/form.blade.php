@extends('layouts.app')

@section('title', 'CFOP | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        <section class="panel">
            <div class="toolbar"><h1>{{ $cfop->exists ? 'Editar CFOP' : 'Novo CFOP' }}</h1><a class="button button-muted" href="{{ route('cfops.index') }}">Voltar</a></div>
            <form method="POST" action="{{ $cfop->exists ? route('cfops.update', $cfop) : route('cfops.store') }}">
                @csrf
                @if ($cfop->exists) @method('PUT') @endif
                <div class="form-grid">
                    <div class="field"><label for="codigo">Código</label><input id="codigo" name="codigo" value="{{ old('codigo', $cfop->codigo) }}" required>@error('codigo') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="tipo_operacao">Tipo</label><select id="tipo_operacao" name="tipo_operacao"><option value="saida" @selected(old('tipo_operacao', $cfop->tipo_operacao) === 'saida')>Saída (5, 6 ou 7)</option><option value="entrada" @selected(old('tipo_operacao', $cfop->tipo_operacao) === 'entrada')>Entrada (1, 2 ou 3)</option></select>@error('tipo_operacao') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field full"><label for="descricao">Descrição</label><input id="descricao" name="descricao" value="{{ old('descricao', $cfop->descricao) }}" required>@error('descricao') <span class="error">{{ $message }}</span> @enderror</div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $cfop->ativo ?? true))>Ativo</label>
                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
