@extends('layouts.app')

@section('title', 'Empresa | Administração')

@section('body')
    <main class="shell">
        <section class="panel">
            <div class="toolbar"><h1>{{ $cliente->exists ? 'Editar empresa' : 'Nova empresa' }}</h1><a class="button button-muted" href="{{ route('admin.empresas.index') }}">Voltar</a></div>
            <form method="POST" action="{{ $cliente->exists ? route('admin.empresas.update', $cliente) : route('admin.empresas.store') }}">
                @csrf
                @if ($cliente->exists) @method('PUT') @endif
                @php($empresa = $cliente->empresas->first())
                <div class="form-grid">
                    <div class="field"><label for="nome">Nome da empresa</label><input id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required>@error('nome') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="slug">Identificador</label><input id="slug" name="slug" value="{{ old('slug', $cliente->slug) }}" required>@error('slug') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="tipo">Tipo</label><select id="tipo" name="tipo"><option value="cliente" @selected(old('tipo', $cliente->tipo) === 'cliente')>Cliente</option><option value="contabilidade" @selected(old('tipo', $cliente->tipo) === 'contabilidade')>Contabilidade</option></select></div>
                    <div class="field"><label for="contabilidade_tenant_id">Contabilidade responsável</label><select id="contabilidade_tenant_id" name="contabilidade_tenant_id"><option value="">Sem vinculo</option>@foreach ($contabilidades as $contabilidade)<option value="{{ $contabilidade->id }}" @selected((string) old('contabilidade_tenant_id', $cliente->contabilidade_tenant_id) === (string) $contabilidade->id)>{{ $contabilidade->nome }}</option>@endforeach</select>@error('contabilidade_tenant_id') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="razao_social">Razão social da empresa</label><input id="razao_social" name="razao_social" value="{{ old('razao_social', $empresa?->razao_social) }}" required>@error('razao_social') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="cnpj">CNPJ</label><input id="cnpj" name="cnpj" value="{{ old('cnpj', $empresa?->cnpj) }}" required>@error('cnpj') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="inscricao_estadual">Inscrição estadual</label><input id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $empresa?->inscricao_estadual) }}"></div>
                    <div class="field"><label for="ambiente_fiscal">Ambiente fiscal</label><select id="ambiente_fiscal" name="ambiente_fiscal"><option value="homologacao" @selected(old('ambiente_fiscal', $empresa?->ambiente_fiscal ?? 'homologacao') === 'homologacao')>Homologacao</option><option value="producao" @selected(old('ambiente_fiscal', $empresa?->ambiente_fiscal) === 'producao')>Producao</option></select></div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $cliente->ativo ?? true))> Ativo</label>
                <button class="button" type="submit">Salvar</button>
            </form>
            @if ($cliente->exists)
                <form method="POST" action="{{ route('admin.empresas.destroy', $cliente) }}" style="margin-top: 16px;" onsubmit="return confirm('Excluir esta empresa somente se nao houver movimento fiscal?');">
                    @csrf @method('DELETE')
                    <button class="button button-muted" type="submit">Excluir ou desabilitar</button>
                </form>
            @endif
        </section>
    </main>
@endsection
