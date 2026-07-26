@extends('layouts.app')

@section('title', 'Usuario administrativo | Administração')

@section('body')
    <main class="shell">
        <section class="panel">
            <div class="toolbar"><h1>{{ $usuario->exists ? 'Editar usuario' : 'Novo usuario' }}</h1><a class="button button-muted" href="{{ route('admin.usuarios.index') }}">Voltar</a></div>
            <form method="POST" action="{{ $usuario->exists ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}">
                @csrf
                @if ($usuario->exists) @method('PUT') @endif
                @php($papelAtual = $usuario->papeis->first()?->slug)
                @php($clienteAtual = $usuario->tenants->first()?->id)
                <div class="form-grid">
                    <div class="field"><label for="nome">Nome</label><input id="nome" name="nome" value="{{ old('nome', $usuario->nome) }}" required>@error('nome') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required>@error('email') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="password">Senha {{ $usuario->exists ? '(deixe vazia para manter)' : '' }}</label><input id="password" name="password" type="password" {{ $usuario->exists ? '' : 'required' }}>@error('password') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="papel">Papel</label><select id="papel" name="papel" required><option value="super_admin_salta" @selected(old('papel', $papelAtual) === 'super_admin_salta')>Super administrador Salta Digital</option><option value="admin_contabilidade" @selected(old('papel', $papelAtual) === 'admin_contabilidade')>Administrador da contabilidade</option><option value="operador_contabilidade" @selected(old('papel', $papelAtual) === 'operador_contabilidade')>Operador da contabilidade</option><option value="cliente_emitente" @selected(old('papel', $papelAtual) === 'cliente_emitente')>Cliente emitente</option></select>@error('papel') <span class="error">{{ $message }}</span> @enderror</div>
                    <div class="field"><label for="tenant_id">Empresa vinculada</label><select id="tenant_id" name="tenant_id"><option value="">Salta Digital / sem empresa</option>@foreach ($clientes as $cliente)<option value="{{ $cliente->id }}" @selected((string) old('tenant_id', $clienteAtual) === (string) $cliente->id)>{{ $cliente->nome }}</option>@endforeach</select>@error('tenant_id') <span class="error">{{ $message }}</span> @enderror</div>
                </div>
                <label class="check"><input name="ativo" type="checkbox" value="1" @checked(old('ativo', $usuario->ativo ?? true))> Ativo</label>
                <button class="button" type="submit">Salvar</button>
            </form>
            @if ($usuario->exists)
                <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" style="margin-top: 16px;" onsubmit="return confirm('Desabilitar este usuario?');">@csrf @method('DELETE')<button class="button button-muted" type="submit">Desabilitar usuario</button></form>
            @endif
        </section>
    </main>
@endsection
