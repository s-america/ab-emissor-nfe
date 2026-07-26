@extends('layouts.app')

@section('title', 'NCM | AB Emissor NF-e')

@section('body')
    @include('partials.topbar')
    <main class="shell">
        <section class="panel">
            <div class="toolbar"><h1>{{ $ncm->exists ? 'Editar NCM' : 'Novo NCM' }}</h1><a class="button button-muted" href="{{ route('ncms.index') }}">Voltar</a></div>
            <form method="POST" action="{{ $ncm->exists ? route('ncms.update', $ncm) : route('ncms.store') }}">
                @csrf
                @if ($ncm->exists) @method('PUT') @endif
                <div class="form-grid">
                    <div class="field"><label for="codigo">Código</label><input id="codigo" name="codigo" value="{{ old('codigo', $ncm->codigo) }}" required>@error('codigo') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field full"><label for="descricao">Descrição</label><input id="descricao" name="descricao" value="{{ old('descricao', $ncm->descricao) }}" required>@error('descricao') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="vigente_de">Vigente de</label><input id="vigente_de" name="vigente_de" type="date" value="{{ old('vigente_de', $ncm->vigente_de?->format('Y-m-d')) }}">@error('vigente_de') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="vigente_ate">Vigente até</label><input id="vigente_ate" name="vigente_ate" type="date" value="{{ old('vigente_ate', $ncm->vigente_ate?->format('Y-m-d')) }}">@error('vigente_ate') <span class="error">{{ $message }}</span> @enderror</div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $ncm->ativo ?? true))>Ativo</label>
                <button class="button" type="submit">Salvar</button>
            </form>
        </section>
    </main>
@endsection
