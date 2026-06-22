<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Controller
 * FILE: app/Http/Controllers/ProdutoController.php
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

use App\Actions\Produtos\SalvarProdutoAction;
use App\Http\Requests\Produtos\SalvarProdutoRequest;
use App\Models\Produto;
use App\Models\User;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function __construct(private readonly EmpresaContextService $empresaContextService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();

        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar produtos.');
        }

        $produtos = Produto::query()
            ->where('empresa_id', $empresa->id)
            ->when($request->string('busca')->toString() !== '', function ($query) use ($request): void {
                $busca = $request->string('busca')->toString();
                $query->where(function ($subquery) use ($busca): void {
                    $subquery->where('descricao', 'like', "%{$busca}%")
                        ->orWhere('codigo', 'like', "%{$busca}%")
                        ->orWhere('ncm', 'like', "%{$busca}%");
                });
            })
            ->orderBy('descricao')
            ->paginate(15)
            ->withQueryString();

        return view('produtos.index', compact('empresa', 'produtos'));
    }

    public function create(): View|RedirectResponse
    {
        $empresa = $this->empresaAtual();

        if ($empresa === null) {
            return redirect()->route('dashboard')->with('status', 'Cadastre uma empresa ativa antes de criar produtos.');
        }

        return view('produtos.form', [
            'empresa' => $empresa,
            'produto' => new Produto(['ativo' => true, 'unidade_comercial' => 'UN', 'origem' => '0', 'valor_unitario' => 0]),
        ]);
    }

    public function store(SalvarProdutoRequest $request, SalvarProdutoAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        /** @var User $usuario */
        $usuario = Auth::user();

        $action->criar($empresa, $usuario, $request->validated(), $request);

        return redirect()->route('produtos.index')->with('status', 'Produto criado com sucesso.');
    }

    public function edit(Produto $produto): View|RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $produto->empresa_id === (int) $empresa->id, 403);

        return view('produtos.form', compact('empresa', 'produto'));
    }

    public function update(SalvarProdutoRequest $request, Produto $produto, SalvarProdutoAction $action): RedirectResponse
    {
        $empresa = $this->empresaAtualOrFail();
        abort_unless((int) $produto->empresa_id === (int) $empresa->id, 403);

        /** @var User $usuario */
        $usuario = Auth::user();

        $action->atualizar($empresa, $usuario, $produto, $request->validated(), $request);

        return redirect()->route('produtos.index')->with('status', 'Produto atualizado com sucesso.');
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
