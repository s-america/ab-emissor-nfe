<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/TransportadoraController.php
 *
 * @package ABEmissor\Controllers
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CadastrosFiscais\SalvarCadastroFiscalAction;
use App\Http\Requests\Transportadoras\SalvarTransportadoraRequest;
use App\Models\Empresa;
use App\Models\Transportadora;
use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransportadoraController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();
        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar transportadoras.');
        }

        $transportadoras = Transportadora::query()
            ->where('empresa_id', $empresa->id)
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

        return view('transportadoras.index', compact('empresa', 'transportadoras'));
    }

    public function create(): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();
        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar transportadoras.');
        }

        return view('transportadoras.form', [
            'empresa' => $empresa,
            'transportadora' => new Transportadora(['ativo' => true]),
        ]);
    }

    public function store(SalvarTransportadoraRequest $request, SalvarCadastroFiscalAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        $action->criar(
            Transportadora::class,
            $request->validated() + ['empresa_id' => $empresa->id],
            'transportadora',
            $empresa,
            $this->usuarioAtual(),
            $request,
        );

        return redirect()->route('transportadoras.index')->with('status', 'Transportadora criada com sucesso.');
    }

    public function edit(Transportadora $transportadora): View
    {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $transportadora->empresa_id === (int) $empresa->id, 403);

        return view('transportadoras.form', compact('empresa', 'transportadora'));
    }

    public function update(
        SalvarTransportadoraRequest $request,
        Transportadora $transportadora,
        SalvarCadastroFiscalAction $action,
    ): RedirectResponse {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $transportadora->empresa_id === (int) $empresa->id, 403);
        $action->atualizar($transportadora, $request->validated(), 'transportadora', $empresa, $this->usuarioAtual(), $request);

        return redirect()->route('transportadoras.index')->with('status', 'Transportadora atualizada com sucesso.');
    }

    private function empresaAtual(): ?Empresa
    {
        return $this->empresaContextService->empresaAtual($this->usuarioAtual());
    }

    private function empresaAtualOrFail(): Empresa
    {
        $empresa = $this->empresaAtual();
        abort_if($empresa === null, 403);

        return $empresa;
    }

    private function usuarioAtual(): User
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        return $usuario;
    }
}
