<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SalvarClienteAdminRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Services\AcessoService;
use App\Services\Empresas\EmpresaContextService;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminClienteController extends Controller
{
    public function __construct(
        private readonly AcessoService $acessoService,
        private readonly EmpresaContextService $empresaContextService,
        private readonly AuditoriaService $auditoriaService,
    )
    {
    }

    public function index(): View
    {
        return view('admin_clientes_index', ['clientes' => $this->clientesVisiveis()->withCount('empresas')->orderBy('nome')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin_clientes_form', [
            'cliente' => new Tenant(['tipo' => 'cliente', 'ativo' => true]),
            'contabilidades' => $this->contabilidadesVisiveis(),
        ]);
    }

    public function store(SalvarClienteAdminRequest $request): RedirectResponse
    {
        $cliente = DB::transaction(function () use ($request): Tenant {
            $dados = $request->safe()->only(['nome', 'slug', 'tipo', 'ativo']);
            if (! $this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
                $dados['tipo'] = 'cliente';
            }
            $dados['contabilidade_tenant_id'] = $this->acessoService->eSuperAdministrador($this->usuarioAtual())
                ? $request->integer('contabilidade_tenant_id') ?: null
                : $this->contabilidadeDoUsuario();
            $cliente = Tenant::query()->create($dados);
            $cliente->empresas()->create($request->safe()->only([
                'razao_social', 'cnpj', 'inscricao_estadual', 'ambiente_fiscal',
            ]) + ['ativo' => true]);

            return $cliente;
        });
        $this->auditoriaService->registrar('admin.empresa.criada', Auth::id(), entidadeTipo: Tenant::class, entidadeId: $cliente->id, request: $request);

        return redirect()->route('admin.empresas.index')->with('status', 'Empresa criada com sucesso.');
    }

    public function edit(Tenant $empresa): View
    {
        $this->autorizarCliente($empresa);

        return view('admin_clientes_form', ['cliente' => $empresa->load('empresas'), 'contabilidades' => $this->contabilidadesVisiveis()]);
    }

    public function update(SalvarClienteAdminRequest $request, Tenant $empresa): RedirectResponse
    {
        $this->autorizarCliente($empresa);
        DB::transaction(function () use ($request, $empresa): void {
            $dados = $request->safe()->only(['nome', 'slug', 'tipo', 'ativo']);
            if (! $this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
                $dados['tipo'] = 'cliente';
            }
            $empresa->update($dados);
            if ($this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
                $empresa->update(['contabilidade_tenant_id' => $request->integer('contabilidade_tenant_id') ?: null]);
            }
            $emitente = $empresa->empresas()->orderBy('id')->firstOrFail();
            $emitente->update($request->safe()->only(['razao_social', 'cnpj', 'inscricao_estadual', 'ambiente_fiscal']));
        });
        $this->auditoriaService->registrar('admin.empresa.atualizada', Auth::id(), entidadeTipo: Tenant::class, entidadeId: $empresa->id, request: $request);

        return redirect()->route('admin.empresas.index')->with('status', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Tenant $empresa): RedirectResponse
    {
        $this->autorizarCliente($empresa);
        if ($empresa->clientesGerenciados()->exists()) {
            $empresa->update(['ativo' => false]);

            return redirect()->route('admin.empresas.index')->with('status', 'Empresa desabilitada: possui outras empresas vinculadas.');
        }
        $empresaIds = $empresa->empresas()->pluck('id');
        $temMovimento = DB::table('fis_nfe_documentos')->whereIn('empresa_id', $empresaIds)->exists()
            || DB::table('fis_nfe_eventos')->whereIn('empresa_id', $empresaIds)->exists()
            || DB::table('fis_certificados_digitais')->whereIn('empresa_id', $empresaIds)->exists();

        if ($temMovimento) {
            $empresa->update(['ativo' => false]);
            $this->auditoriaService->registrar('admin.empresa.desabilitada', Auth::id(), entidadeTipo: Tenant::class, entidadeId: $empresa->id, request: request());

            return redirect()->route('admin.empresas.index')->with('status', 'Empresa desabilitada: ja possui movimento fiscal e nao pode ser excluida.');
        }

        DB::transaction(function () use ($empresa, $empresaIds): void {
            DB::table('log_auditorias')->whereIn('empresa_id', $empresaIds)->update(['empresa_id' => null, 'tenant_id' => null]);
            DB::table('sis_tenant_usuarios')->where('tenant_id', $empresa->id)->delete();
            DB::table('cad_destinatarios')->whereIn('empresa_id', $empresaIds)->delete();
            DB::table('cad_produtos')->whereIn('empresa_id', $empresaIds)->delete();
            DB::table('cad_transportadoras')->whereIn('empresa_id', $empresaIds)->delete();
            DB::table('cad_naturezas_operacao')->whereIn('empresa_id', $empresaIds)->delete();
            DB::table('cad_empresas')->whereIn('id', $empresaIds)->delete();
            $empresa->delete();
        });
        $this->auditoriaService->registrar('admin.empresa.excluida', Auth::id(), entidadeTipo: Tenant::class, entidadeId: $empresa->id, request: request());

        return redirect()->route('admin.empresas.index')->with('status', 'Empresa excluida por nao possuir movimento fiscal.');
    }

    private function clientesVisiveis(): \Illuminate\Database\Eloquent\Builder
    {
        $usuario = $this->usuarioAtual();
        if ($this->acessoService->eSuperAdministrador($usuario)) {
            return Tenant::query();
        }

        $contabilidadeId = $this->contabilidadeDoUsuario();

        return Tenant::query()->where('contabilidade_tenant_id', $contabilidadeId);
    }

    private function autorizarCliente(Tenant $cliente): void
    {
        abort_unless($this->clientesVisiveis()->whereKey($cliente->id)->exists(), 403);
    }

    private function contabilidadeDoUsuario(): ?int
    {
        $usuario = $this->usuarioAtual();
        if ($this->acessoService->eSuperAdministrador($usuario)) {
            return null;
        }

        return $this->empresaContextService->clienteAtual($usuario)?->id;
    }

    private function contabilidadesVisiveis(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
            return Tenant::query()->where('tipo', 'contabilidade')->where('ativo', true)->orderBy('nome')->get();
        }

        $cliente = $this->empresaContextService->clienteAtual($this->usuarioAtual());

        return $cliente === null ? new \Illuminate\Database\Eloquent\Collection() : new \Illuminate\Database\Eloquent\Collection([$cliente]);
    }

    private function usuarioAtual(): User
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        return $usuario;
    }
}
