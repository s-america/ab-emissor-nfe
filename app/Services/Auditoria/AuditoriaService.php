<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Service
 * FILE: app/Services/Auditoria/AuditoriaService.php
 *
 * @package ABEmissor\Services
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licencas.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/08-seguranca.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Services\Auditoria;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaService
{
    /**
     * @param array<string, mixed> $metadados
     */
    public function registrar(
        string $acao,
        ?int $usuarioId = null,
        ?int $tenantId = null,
        ?int $empresaId = null,
        ?string $entidadeTipo = null,
        ?int $entidadeId = null,
        array $metadados = [],
        ?Request $request = null,
    ): void {
        Auditoria::query()->create([
            'Tenant_Id' => $tenantId,
            'Empresa_Id' => $empresaId,
            'Usuario_Id' => $usuarioId,
            'acao' => $acao,
            'entidade_tipo' => $entidadeTipo,
            'entidade_id' => $entidadeId,
            'metadados' => $metadados,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
