<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/DestinatarioController.php
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
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Destinatarios\SalvarDestinatarioAction;
use App\Http\Requests\Destinatarios\SalvarDestinatarioRequest;
use App\Models\Destinatario;
use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DestinatarioController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();

        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar destinatarios.');
        }

        $destinatarios = Destinatario::query()
            ->where('Empresa_Id', $empresa->id)
            ->when($request->string('busca')->toString() !== '', function ($query) use ($request): void {
                $busca = $request->string('busca')->toString();
                $query->where(function ($subquery) use ($busca): void {
                    $subquery->where('nome_razao_social', 'like', "%{$busca}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$busca}%");
                });
            })
            ->orderBy('nome_razao_social')
            ->paginate(15)
            ->withQueryString();

        return view('destinatarios.index', compact('empresa', 'destinatarios'));
    }

    public function create(): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();

        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar destinatarios.');
        }

        return view('destinatarios.form', [
            'empresa' => $empresa,
            'destinatario' => new Destinatario(['ativo' => true, 'indicador_ie' => 'nao_contribuinte']),
        ]);
    }

    public function store(SalvarDestinatarioRequest $request, SalvarDestinatarioAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        /** @var User $usuario */
        $usuario = Auth::user();

        $action->criar($empresa, $usuario, $request->validated(), $request);

        return redirect()->route('destinatarios.index')->with('status', 'Destinatario criado com sucesso.');
    }

    public function edit(Destinatario $destinatario): View|RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();

        abort_unless((int) $destinatario->Empresa_Id === (int) $empresa->id, 403);

        return view('destinatarios.form', compact('empresa', 'destinatario'));
    }

    public function update(SalvarDestinatarioRequest $request, Destinatario $destinatario, SalvarDestinatarioAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $destinatario->Empresa_Id === (int) $empresa->id, 403);

        /** @var User $usuario */
        $usuario = Auth::user();

        $action->atualizar($empresa, $usuario, $destinatario, $request->validated(), $request);

        return redirect()->route('destinatarios.index')->with('status', 'Destinatario atualizado com sucesso.');
    }

    private function empresaAtual(): ?\App\Models\Empresa
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        return $this->empresaContextService->empresaAtual($usuario);
    }

    private function empresaAtualOrFail(): \App\Models\Empresa
    {
        $empresa = $this->empresaAtual();

        abort_if($empresa === null, 403);

        return $empresa;
    }
}
