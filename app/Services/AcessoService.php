<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AcessoService
{
    public function possuiPapel(User $usuario, string $slug): bool
    {
        return $usuario->papeis()->where('sis_papeis.slug', $slug)->where('sis_papeis.ativo', true)->exists();
    }

    public function eSuperAdministrador(User $usuario): bool
    {
        return $this->possuiPapel($usuario, 'super_admin_salta');
    }

    public function podeAdministrarClientes(User $usuario): bool
    {
        return $this->eSuperAdministrador($usuario) || $this->possuiPapel($usuario, 'admin_contabilidade');
    }

    public function painelInicial(User $usuario): string
    {
        return $this->eSuperAdministrador($usuario) ? 'admin.empresas.index' : 'dashboard';
    }
}
