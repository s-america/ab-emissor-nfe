<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sis_tenants', function (Blueprint $table): void {
            $table->foreignId('contabilidade_tenant_id')
                ->nullable()
                ->after('tipo')
                ->constrained('sis_tenants')
                ->nullOnDelete();
            $table->index(['contabilidade_tenant_id', 'ativo']);
        });
    }

    public function down(): void
    {
        Schema::table('sis_tenants', function (Blueprint $table): void {
            $table->dropForeign(['contabilidade_tenant_id']);
            $table->dropIndex(['contabilidade_tenant_id', 'ativo']);
            $table->dropColumn('contabilidade_tenant_id');
        });
    }
};
