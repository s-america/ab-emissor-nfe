<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/NaturezaOperacaoController.php
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
use App\Http\Requests\NaturezasOperacao\SalvarNaturezaOperacaoRequest;
use App\Models\Cfop;
use App\Models\Empresa;
use App\Models\NaturezaOperacao;
use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NaturezaOperacaoController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();
        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar naturezas de operacao.');
        }

        $naturezasOperacao = NaturezaOperacao::query()
            ->where('empresa_id', $empresa->id)
            ->when($request->string('busca')->toString() !== '', fn ($query) => $query->where('descricao', 'like', '%'.$request->string('busca')->toString().'%'))
            ->orderBy('descricao')
            ->paginate(15)
            ->withQueryString();

        return view('naturezas-operacao.index', compact('empresa', 'naturezasOperacao'));
    }

    public function create(): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();
        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar naturezas de operacao.');
        }

        return view('naturezas-operacao.form', [
            'empresa' => $empresa,
            'naturezaOperacao' => new NaturezaOperacao(['ativo' => true, 'tipo_operacao' => 'saida']),
            'cfops' => Cfop::query()->where('ativo', true)->orderBy('codigo')->get(),
        ]);
    }

    public function store(SalvarNaturezaOperacaoRequest $request, SalvarCadastroFiscalAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        $action->criar(
            NaturezaOperacao::class,
            $request->validated() + ['empresa_id' => $empresa->id],
            'natureza_operacao',
            $empresa,
            $this->usuarioAtual(),
            $request,
        );

        return redirect()->route('naturezas-operacao.index')->with('status', 'Natureza de operacao criada com sucesso.');
    }

    public function edit(NaturezaOperacao $naturezaOperacao): View
    {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $naturezaOperacao->empresa_id === (int) $empresa->id, 403);

        return view('naturezas-operacao.form', [
            'empresa' => $empresa,
            'naturezaOperacao' => $naturezaOperacao,
            'cfops' => Cfop::query()->where('ativo', true)->orderBy('codigo')->get(),
        ]);
    }

    public function update(
        SalvarNaturezaOperacaoRequest $request,
        NaturezaOperacao $naturezaOperacao,
        SalvarCadastroFiscalAction $action,
    ): RedirectResponse {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $naturezaOperacao->empresa_id === (int) $empresa->id, 403);
        $action->atualizar($naturezaOperacao, $request->validated(), 'natureza_operacao', $empresa, $this->usuarioAtual(), $request);

        return redirect()->route('naturezas-operacao.index')->with('status', 'Natureza de operacao atualizada com sucesso.');
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
