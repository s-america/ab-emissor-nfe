<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: database/migrations/2026_06_21_000000_rename_laravel_default_tables_to_ab_emissor.php
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
 * @see /docs/07-modelagem-banco.md
 * @deprecated false
 */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameTableIfNeeded('users', 'ABE_Usuarios');
        $this->renameTableIfNeeded('password_reset_tokens', 'ABE_PasswordResetTokens');
        $this->renameTableIfNeeded('sessions', 'ABE_Sessoes');
        $this->renameTableIfNeeded('cache', 'ABE_Caches');
        $this->renameTableIfNeeded('cache_locks', 'ABE_CacheLocks');
        $this->renameTableIfNeeded('jobs', 'ABE_Jobs');
        $this->renameTableIfNeeded('job_batches', 'ABE_JobBatches');
        $this->renameTableIfNeeded('failed_jobs', 'ABE_FailedJobs');

        if (Schema::hasTable('ABE_Usuarios')) {
            Schema::table('ABE_Usuarios', function (Blueprint $table): void {
                if (Schema::hasColumn('ABE_Usuarios', 'name') && ! Schema::hasColumn('ABE_Usuarios', 'nome')) {
                    $table->renameColumn('name', 'nome');
                }

                if (! Schema::hasColumn('ABE_Usuarios', 'ativo')) {
                    $table->boolean('ativo')->default(true)->after('password');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ABE_Usuarios')) {
            Schema::table('ABE_Usuarios', function (Blueprint $table): void {
                if (Schema::hasColumn('ABE_Usuarios', 'nome') && ! Schema::hasColumn('ABE_Usuarios', 'name')) {
                    $table->renameColumn('nome', 'name');
                }

                if (Schema::hasColumn('ABE_Usuarios', 'ativo')) {
                    $table->dropColumn('ativo');
                }
            });
        }

        $this->renameTableIfNeeded('ABE_FailedJobs', 'failed_jobs');
        $this->renameTableIfNeeded('ABE_JobBatches', 'job_batches');
        $this->renameTableIfNeeded('ABE_Jobs', 'jobs');
        $this->renameTableIfNeeded('ABE_CacheLocks', 'cache_locks');
        $this->renameTableIfNeeded('ABE_Caches', 'cache');
        $this->renameTableIfNeeded('ABE_Sessoes', 'sessions');
        $this->renameTableIfNeeded('ABE_PasswordResetTokens', 'password_reset_tokens');
        $this->renameTableIfNeeded('ABE_Usuarios', 'users');
    }

    private function renameTableIfNeeded(string $from, string $to): void
    {
        if (Schema::hasTable($from) && ! Schema::hasTable($to)) {
            Schema::rename($from, $to);
        }
    }
};
