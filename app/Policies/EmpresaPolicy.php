<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: app/Policies/EmpresaPolicy.php
 *
 * @package ABEmissor\Core
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

namespace App\Policies;

use App\Models\Empresa;
use App\Models\User;

class EmpresaPolicy
{
    public function view(User $usuario, Empresa $empresa): bool
    {
        return $usuario->tenants()
            ->where('SIS_Tenants.id', $empresa->Tenant_Id)
            ->wherePivot('ativo', true)
            ->exists();
    }

    public function update(User $usuario, Empresa $empresa): bool
    {
        return $usuario->tenants()
            ->where('SIS_Tenants.id', $empresa->Tenant_Id)
            ->wherePivot('ativo', true)
            ->wherePivotIn('perfil', ['admin_tecnico', 'admin_contabilidade'])
            ->exists();
    }
}
