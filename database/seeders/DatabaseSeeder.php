<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: database/seeders/DatabaseSeeder.php
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
 * @see /docs/arquitetura-ab-emissor-nfe-v1.0.md
 * @deprecated false
 */

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $usuario = User::query()->firstOrCreate(
                ['email' => 'admin@abemissor.local'],
                [
                    'nome' => 'Administrador AB Emissor',
                    'password' => 'password',
                    'ativo' => true,
                ],
            );

            DB::table('SIS_Tenants')->updateOrInsert(
                ['slug' => 'ab-contabilidade'],
                [
                    'nome' => 'AB Contabilidade',
                    'tipo' => 'contabilidade',
                    'ativo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );

            $tenantId = (int) DB::table('SIS_Tenants')->where('slug', 'ab-contabilidade')->value('id');

            DB::table('SIS_TenantUsuarios')->updateOrInsert(
                [
                    'Tenant_Id' => $tenantId,
                    'Usuario_Id' => $usuario->id,
                ],
                [
                    'perfil' => 'admin_contabilidade',
                    'ativo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        });
    }
}
