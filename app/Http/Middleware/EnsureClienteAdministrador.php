<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\AcessoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClienteAdministrador
{
    public function __construct(private readonly AcessoService $acessoService)
    {
    }

    /** @param Closure(Request): mixed $next */
    public function handle(Request $request, Closure $next): mixed
    {
        abort_unless(Auth::check() && $this->acessoService->podeAdministrarClientes(Auth::user()), 403);

        return $next($request);
    }
}
