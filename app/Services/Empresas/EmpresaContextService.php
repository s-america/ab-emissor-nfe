<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Service
 * FILE: app/Services/Empresas/EmpresaContextService.php
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
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Services\Empresas;

use App\Models\Empresa;
use App\Models\User;

class EmpresaContextService
{
    public function empresaAtual(User $usuario): ?Empresa
    {
        $tenantIds = $usuario->tenants()
            ->wherePivot('ativo', true)
            ->pluck('SIS_Tenants.id');

        if ($tenantIds->isEmpty()) {
            return null;
        }

        return Empresa::query()
            ->whereIn('Tenant_Id', $tenantIds)
            ->where('ativo', true)
            ->orderBy('razao_social')
            ->first();
    }
}
