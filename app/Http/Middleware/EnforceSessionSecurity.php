<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Middleware
 * FILE: app/Http/Middleware/EnforceSessionSecurity.php
 *
 * @package ABEmissor\Middleware
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/08-seguranca.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Auditoria\AuditoriaService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceSessionSecurity
{
    public function __construct(private readonly AuditoriaService $auditoriaService)
    {
    }

    /**
     * @param Closure(Request): mixed $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $agora = time();
        $inicio = (int) $request->session()->get('auth_started_at', $agora);
        $ultimaAtividade = (int) $request->session()->get('auth_last_activity_at', $agora);
        $ultimaRenovacao = (int) $request->session()->get('auth_rotated_at', $agora);

        $limiteInatividade = (int) config('security.session.idle_timeout_minutes', 30) * 60;
        $limiteAbsoluto = (int) config('security.session.absolute_timeout_minutes', 480) * 60;
        $limiteRenovacao = (int) config('security.session.renewal_timeout_minutes', 30) * 60;

        if (($agora - $ultimaAtividade) >= $limiteInatividade || ($agora - $inicio) >= $limiteAbsoluto) {
            return $this->expirarSessao($request);
        }

        if (($agora - $ultimaRenovacao) >= $limiteRenovacao) {
            $request->session()->migrate(true);
            $request->session()->put('auth_rotated_at', $agora);
        }

        $request->session()->put([
            'auth_started_at' => $inicio,
            'auth_last_activity_at' => $agora,
            'auth_rotated_at' => (int) $request->session()->get('auth_rotated_at', $ultimaRenovacao),
        ]);

        return $next($request);
    }

    private function expirarSessao(Request $request): RedirectResponse
    {
        $usuarioId = Auth::id();

        if ($usuarioId !== null) {
            $this->auditoriaService->registrar(
                acao: 'auth.sessao_expirada',
                usuarioId: (int) $usuarioId,
                request: $request,
            );
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Sua sessao expirou. Entre novamente.');
    }
}
