<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Action
 * FILE: app/Actions/Auth/AutenticarUsuarioAction.php
 *
 * @package ABEmissor\Actions
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licencas.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/08-seguranca.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AutenticarUsuarioAction
{
    public function __construct(private readonly AuditoriaService $auditoriaService)
    {
    }

    /**
     * @param array{email: string, password: string, remember?: bool} $dados
     */
    public function executar(array $dados, Request $request): void
    {
        $rateLimitKey = $this->rateLimitKey($request);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            event(new Lockout($request));

            throw ValidationException::withMessages([
                'email' => 'Muitas tentativas de acesso. Aguarde alguns segundos e tente novamente.',
            ]);
        }

        if (! Auth::attempt(['email' => $dados['email'], 'password' => $dados['password'], 'ativo' => true], (bool) ($dados['remember'] ?? false))) {
            RateLimiter::hit($rateLimitKey);

            throw ValidationException::withMessages([
                'email' => 'Credenciais invalidas ou usuario inativo.',
            ]);
        }

        RateLimiter::clear($rateLimitKey);
        $request->session()->regenerate();

        /** @var User $usuario */
        $usuario = Auth::user();

        $this->auditoriaService->registrar(
            acao: 'auth.login',
            usuarioId: (int) $usuario->id,
            metadados: ['email' => $usuario->email],
            request: $request,
        );
    }

    private function rateLimitKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('email')).'|'.$request->ip());
    }
}
