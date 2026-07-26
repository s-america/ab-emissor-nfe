<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Middleware
 * FILE: app/Http/Middleware/EnsureClienteAtivo.php
 *
 * @package ABEmissor\Middleware
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/12-governanca-clientes-seguranca.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClienteAtivo
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    /**
     * @param Closure(Request): mixed $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User|null $usuario */
        $usuario = Auth::user();


        if ($usuario !== null && $this->empresaContextService->clienteAtual($usuario) === null) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status',
                'Seu acesso nao possui um cliente ativo. Entre em contato com o administrador.',
            );
        }

        return $next($request);
    }
}
