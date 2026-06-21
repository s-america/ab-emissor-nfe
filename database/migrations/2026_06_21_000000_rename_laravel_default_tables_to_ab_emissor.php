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
 * @see /docs/arquitetura-ab-emissor-nfe-v1.0.md
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
        $this->renameTableIfNeeded('users', 'SIS_Usuarios');
        $this->renameTableIfNeeded('ABE_Usuarios', 'SIS_Usuarios');
        $this->renameTableIfNeeded('password_reset_tokens', 'SIS_PasswordResetTokens');
        $this->renameTableIfNeeded('ABE_PasswordResetTokens', 'SIS_PasswordResetTokens');
        $this->renameTableIfNeeded('sessions', 'SIS_Sessoes');
        $this->renameTableIfNeeded('ABE_Sessoes', 'SIS_Sessoes');
        $this->renameTableIfNeeded('cache', 'SIS_Caches');
        $this->renameTableIfNeeded('ABE_Caches', 'SIS_Caches');
        $this->renameTableIfNeeded('cache_locks', 'SIS_CacheLocks');
        $this->renameTableIfNeeded('ABE_CacheLocks', 'SIS_CacheLocks');
        $this->renameTableIfNeeded('jobs', 'SIS_Jobs');
        $this->renameTableIfNeeded('ABE_Jobs', 'SIS_Jobs');
        $this->renameTableIfNeeded('job_batches', 'SIS_JobBatches');
        $this->renameTableIfNeeded('ABE_JobBatches', 'SIS_JobBatches');
        $this->renameTableIfNeeded('failed_jobs', 'SIS_FailedJobs');
        $this->renameTableIfNeeded('ABE_FailedJobs', 'SIS_FailedJobs');

        if (Schema::hasTable('SIS_Usuarios')) {
            Schema::table('SIS_Usuarios', function (Blueprint $table): void {
                if (Schema::hasColumn('SIS_Usuarios', 'name') && ! Schema::hasColumn('SIS_Usuarios', 'nome')) {
                    $table->renameColumn('name', 'nome');
                }

                if (! Schema::hasColumn('SIS_Usuarios', 'ativo')) {
                    $table->boolean('ativo')->default(true)->after('password');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('SIS_Usuarios')) {
            Schema::table('SIS_Usuarios', function (Blueprint $table): void {
                if (Schema::hasColumn('SIS_Usuarios', 'nome') && ! Schema::hasColumn('SIS_Usuarios', 'name')) {
                    $table->renameColumn('nome', 'name');
                }

                if (Schema::hasColumn('SIS_Usuarios', 'ativo')) {
                    $table->dropColumn('ativo');
                }
            });
        }

        $this->renameTableIfNeeded('SIS_FailedJobs', 'failed_jobs');
        $this->renameTableIfNeeded('SIS_JobBatches', 'job_batches');
        $this->renameTableIfNeeded('SIS_Jobs', 'jobs');
        $this->renameTableIfNeeded('SIS_CacheLocks', 'cache_locks');
        $this->renameTableIfNeeded('SIS_Caches', 'cache');
        $this->renameTableIfNeeded('SIS_Sessoes', 'sessions');
        $this->renameTableIfNeeded('SIS_PasswordResetTokens', 'password_reset_tokens');
        $this->renameTableIfNeeded('SIS_Usuarios', 'users');
    }

    private function renameTableIfNeeded(string $from, string $to): void
    {
        if (Schema::hasTable($from) && ! Schema::hasTable($to)) {
            Schema::rename($from, $to);
        }
    }
};
