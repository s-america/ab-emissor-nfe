<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: bootstrap/app.php
 *
 * @package ABEmissor\Core
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licencas.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/02-arquitetura.md
 * @deprecated false
 */

declare(strict_types=1);

use App\Http\Middleware\EnsureUsuarioAtivo;
use App\Http\Middleware\EnsureClienteAtivo;
use App\Http\Middleware\EnforceSessionSecurity;
use App\Http\Middleware\EnsureSuperAdministrador;
use App\Http\Middleware\EnsureClienteAdministrador;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'usuario.ativo' => EnsureUsuarioAtivo::class,
            'cliente.ativo' => EnsureClienteAtivo::class,
            'sessao.segura' => EnforceSessionSecurity::class,
            'super.admin' => EnsureSuperAdministrador::class,
            'cliente.admin' => EnsureClienteAdministrador::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
