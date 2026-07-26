<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/CfopController.php
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
use App\Http\Requests\Cfops\SalvarCfopRequest;
use App\Models\Cfop;
use App\Models\Empresa;
use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CfopController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function index(Request $request): View
    {
        $cfops = Cfop::query()
            ->when($request->string('busca')->toString() !== '', function ($query) use ($request): void {
                $busca = $request->string('busca')->toString();
                $query->where(fn ($subquery) => $subquery->where('codigo', 'like', "%{$busca}%")->orWhere('descricao', 'like', "%{$busca}%"));
            })
            ->orderBy('codigo')
            ->paginate(20)
            ->withQueryString();

        return view('cfops.index', [
            'cfops' => $cfops,
            'podeAdministrar' => $this->podeAdministrarCatalogo(),
        ]);
    }

    public function create(): View
    {
        $this->autorizarAdministracaoCatalogo();

        return view('cfops.form', ['cfop' => new Cfop(['ativo' => true, 'tipo_operacao' => 'saida'])]);
    }

    public function store(SalvarCfopRequest $request, SalvarCadastroFiscalAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        $this->autorizarAdministracaoCatalogo($empresa);
        $action->criar(Cfop::class, $request->validated(), 'cfop', $empresa, $this->usuarioAtual(), $request);

        return redirect()->route('cfops.index')->with('status', 'CFOP criado com sucesso.');
    }

    public function edit(Cfop $cfop): View
    {
        $this->autorizarAdministracaoCatalogo();

        return view('cfops.form', compact('cfop'));
    }

    public function update(SalvarCfopRequest $request, Cfop $cfop, SalvarCadastroFiscalAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        $this->autorizarAdministracaoCatalogo($empresa);
        $action->atualizar($cfop, $request->validated(), 'cfop', $empresa, $this->usuarioAtual(), $request);

        return redirect()->route('cfops.index')->with('status', 'CFOP atualizado com sucesso.');
    }

    private function empresaAtualOrFail(): Empresa
    {
        $empresa = $this->empresaContextService->empresaAtual($this->usuarioAtual());
        abort_if($empresa === null, 403);

        return $empresa;
    }

    private function usuarioAtual(): User
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        return $usuario;
    }

    private function autorizarAdministracaoCatalogo(?Empresa $empresa = null): void
    {
        abort_unless($this->podeAdministrarCatalogo($empresa), 403);
    }

    private function podeAdministrarCatalogo(?Empresa $empresa = null): bool
    {
        $empresa ??= $this->empresaContextService->empresaAtual($this->usuarioAtual());
        if ($empresa === null) {
            return false;
        }

        return $this->usuarioAtual()->tenants()
            ->whereKey($empresa->tenant_id)
            ->wherePivot('ativo', true)
            ->wherePivot('perfil', 'admin_contabilidade')
            ->exists();
    }
}
