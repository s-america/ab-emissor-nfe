<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sis_papeis', function (Blueprint $table): void {
            $table->string('escopo', 30)->default('cliente')->after('slug');
            $table->index(['escopo', 'ativo']);
        });
    }

    public function down(): void
    {
        Schema::table('sis_papeis', function (Blueprint $table): void {
            $table->dropIndex(['escopo', 'ativo']);
            $table->dropColumn('escopo');
        });
    }
};
