<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SalvarUsuarioAdminRequest;
use App\Models\Papel;
use App\Models\Tenant;
use App\Models\User;
use App\Services\AcessoService;
use App\Services\Empresas\EmpresaContextService;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminUsuarioController extends Controller
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
        return view('admin_usuarios_index', [
            'usuarios' => $this->usuariosVisiveis()->with(['papeis', 'tenants'])->orderBy('nome')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin_usuarios_form', [
            'usuario' => new User(['ativo' => true]),
            'clientes' => $this->clientesVisiveis(),
        ]);
    }

    public function store(SalvarUsuarioAdminRequest $request): RedirectResponse
    {
        $usuario = DB::transaction(function () use ($request): User {
            $dados = $request->safe()->only(['nome', 'email', 'password', 'ativo']);
            $usuario = User::query()->create($dados);
            $this->sincronizarAcesso($usuario, (string) $request->validated('papel'), $request->integer('tenant_id'));

            return $usuario;
        });
        $this->auditoriaService->registrar('admin.usuario.criado', Auth::id(), entidadeTipo: User::class, entidadeId: $usuario->id, request: $request);

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario administrativo criado com sucesso.');
    }

    public function edit(User $usuario): View
    {
        $this->autorizarUsuario($usuario);

        return view('admin_usuarios_form', [
            'usuario' => $usuario->load(['papeis', 'tenants']),
            'clientes' => $this->clientesVisiveis(),
        ]);
    }

    public function update(SalvarUsuarioAdminRequest $request, User $usuario): RedirectResponse
    {
        $this->autorizarUsuario($usuario);
        if (! $this->acessoService->eSuperAdministrador($this->usuarioAtual()) && $request->validated('papel') === 'super_admin_salta') {
            return back()->withErrors(['papel' => 'A contabilidade nao pode criar super administradores.']);
        }
        if ((int) Auth::id() === (int) $usuario->id && $request->validated('papel') !== 'super_admin_salta') {
            return back()->withErrors(['papel' => 'O super administrador atual nao pode remover o proprio acesso.']);
        }

        DB::transaction(function () use ($request, $usuario): void {
            $dados = $request->safe()->only(['nome', 'email', 'ativo']);
            if ($request->filled('password')) {
                $dados['password'] = $request->validated('password');
            }
            $usuario->update($dados);
            $this->sincronizarAcesso($usuario, (string) $request->validated('papel'), $request->integer('tenant_id'));
        });
        $this->auditoriaService->registrar('admin.usuario.atualizado', Auth::id(), entidadeTipo: User::class, entidadeId: $usuario->id, request: $request);

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario atualizado com sucesso.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        $this->autorizarUsuario($usuario);
        if ((int) Auth::id() === (int) $usuario->id) {
            return back()->withErrors(['usuario' => 'Nao e permitido desabilitar o proprio usuario.']);
        }

        $usuario->update(['ativo' => false]);
        $this->auditoriaService->registrar('admin.usuario.desabilitado', Auth::id(), entidadeTipo: User::class, entidadeId: $usuario->id, request: request());

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario desabilitado com preservacao do historico.');
    }

    private function sincronizarAcesso(User $usuario, string $papelSlug, ?int $tenantId): void
    {
        if (! $this->acessoService->eSuperAdministrador($this->usuarioAtual()) && $papelSlug === 'super_admin_salta') {
            abort(403);
        }
        $papel = Papel::query()->where('slug', $papelSlug)->where('ativo', true)->firstOrFail();
        $usuario->papeis()->sync([$papel->id]);
        $usuario->tenants()->detach();

        if ($papelSlug !== 'super_admin_salta') {
            abort_unless($tenantId !== null, 422, 'Um usuario operacional precisa estar vinculado a um cliente.');
            abort_unless($this->clientesVisiveis()->whereKey($tenantId)->exists(), 403);
            $usuario->tenants()->attach($tenantId, [
                'perfil' => $papelSlug,
                'ativo' => true,
            ]);
        }
    }

    private function usuariosVisiveis(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
            return User::query();
        }

        $ids = $this->clientesVisiveis()->pluck('id');

        return User::query()->whereHas('tenants', fn ($query) => $query->whereIn('sis_tenants.id', $ids));
    }

    private function clientesVisiveis(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->acessoService->eSuperAdministrador($this->usuarioAtual())) {
            return Tenant::query()->where('ativo', true);
        }

        $contabilidade = $this->empresaContextService->clienteAtual($this->usuarioAtual());

        return Tenant::query()->where('contabilidade_tenant_id', $contabilidade?->id)->where('ativo', true);
    }

    private function autorizarUsuario(User $usuario): void
    {
        abort_unless($this->usuariosVisiveis()->whereKey($usuario->id)->exists(), 403);
    }

    private function usuarioAtual(): User
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        return $usuario;
    }
}
