<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/Auth/LoginController.php
 *
 * @package ABEmissor\Controllers
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

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AutenticarUsuarioAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, AutenticarUsuarioAction $autenticarUsuarioAction): RedirectResponse
    {
        $autenticarUsuarioAction->executar($request->validated(), $request);

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request, AuditoriaService $auditoriaService): RedirectResponse
    {
        $usuarioId = Auth::id();

        if ($usuarioId !== null) {
            $auditoriaService->registrar(
                acao: 'auth.logout',
                usuarioId: (int) $usuarioId,
                request: $request,
            );
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
