<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/DashboardController.php
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

namespace App\Http\Controllers;

use App\Models\Destinatario;
use App\Models\Produto;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function __invoke(): View
    {
        /** @var Authenticatable&\App\Models\User $usuario */
        $usuario = Auth::user();
        $cliente = $this->empresaContextService->clienteAtual($usuario);
        $empresa = $this->empresaContextService->empresaAtual($usuario);

        return view('dashboard.index', [
            'usuario' => $usuario,
            'cliente' => $cliente,
            'empresa' => $empresa,
            'totalDestinatarios' => $empresa === null
                ? 0
                : Destinatario::query()->where('empresa_id', $empresa->id)->count(),
            'totalProdutos' => $empresa === null
                ? 0
                : Produto::query()->where('empresa_id', $empresa->id)->count(),
        ]);
    }
}
