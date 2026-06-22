<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Action
 * FILE: app/Actions/Destinatarios/SalvarDestinatarioAction.php
 *
 * @package ABEmissor\Actions
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

namespace App\Actions\Destinatarios;

use App\Models\Destinatario;
use App\Models\Empresa;
use App\Models\User;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\Request;

class SalvarDestinatarioAction
{
    public function __construct(private readonly AuditoriaService $auditoriaService)
    {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function criar(Empresa $empresa, User $usuario, array $dados, Request $request): Destinatario
    {
        $destinatario = Destinatario::query()->create($dados + ['Empresa_Id' => $empresa->id]);

        $this->registrarAuditoria('destinatario.criado', $empresa, $usuario, $destinatario, $request);

        return $destinatario;
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function atualizar(Empresa $empresa, User $usuario, Destinatario $destinatario, array $dados, Request $request): Destinatario
    {
        $destinatario->fill($dados);
        $destinatario->save();

        $this->registrarAuditoria('destinatario.atualizado', $empresa, $usuario, $destinatario, $request);

        return $destinatario;
    }

    private function registrarAuditoria(string $acao, Empresa $empresa, User $usuario, Destinatario $destinatario, Request $request): void
    {
        $this->auditoriaService->registrar(
            acao: $acao,
            usuarioId: (int) $usuario->id,
            tenantId: (int) $empresa->Tenant_Id,
            empresaId: (int) $empresa->id,
            entidadeTipo: Destinatario::class,
            entidadeId: (int) $destinatario->id,
            request: $request,
        );
    }
}
