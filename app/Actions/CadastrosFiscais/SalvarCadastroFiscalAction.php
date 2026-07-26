<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Action
 * FILE: app/Actions/CadastrosFiscais/SalvarCadastroFiscalAction.php
 *
 * @package ABEmissor\Actions
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Actions\CadastrosFiscais;

use App\Models\Empresa;
use App\Models\User;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SalvarCadastroFiscalAction
{
    public function __construct(private readonly AuditoriaService $auditoriaService)
    {
    }

    /**
     * @param class-string<Model> $modelClass
     * @param array<string, mixed> $dados
     */
    public function criar(
        string $modelClass,
        array $dados,
        string $acao,
        Empresa $empresa,
        User $usuario,
        Request $request,
    ): Model {
        $model = $modelClass::query()->create($dados);
        $this->registrarAuditoria($acao.'.criado', $model, $empresa, $usuario, $request);

        return $model;
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function atualizar(
        Model $model,
        array $dados,
        string $acao,
        Empresa $empresa,
        User $usuario,
        Request $request,
    ): Model {
        $model->fill($dados);
        $model->save();
        $this->registrarAuditoria($acao.'.atualizado', $model, $empresa, $usuario, $request);

        return $model;
    }

    private function registrarAuditoria(
        string $acao,
        Model $model,
        Empresa $empresa,
        User $usuario,
        Request $request,
    ): void {
        $this->auditoriaService->registrar(
            acao: $acao,
            usuarioId: (int) $usuario->id,
            tenantId: (int) $empresa->tenant_id,
            empresaId: (int) $empresa->id,
            entidadeTipo: $model::class,
            entidadeId: (int) $model->getKey(),
            request: $request,
        );
    }
}
