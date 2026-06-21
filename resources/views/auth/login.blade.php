@extends('layouts.app')

@section('title', 'Login | AB Emissor NF-e')

@section('body')
    <main class="auth-wrap">
        <section class="auth-box panel">
            <h1>AB Emissor NF-e</h1>
            <p class="muted">Acesso seguro ao nucleo multiempresa.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="field">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="check">
                    <input name="remember" type="checkbox" value="1">
                    Manter conectado
                </label>

                <button class="button" type="submit">Entrar</button>
            </form>
        </section>
    </main>
@endsection
