<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: database/migrations/2026_06_21_000001_create_ab_emissor_base_tables.php
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
        Schema::create('SIS_Tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('tipo', 30)->default('cliente');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('SIS_Papeis', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('SIS_Permissoes', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('SIS_PapelPermissoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Papel_Id')->constrained('SIS_Papeis')->cascadeOnDelete();
            $table->foreignId('Permissao_Id')->constrained('SIS_Permissoes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['Papel_Id', 'Permissao_Id']);
        });

        Schema::create('SIS_UsuarioPapeis', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Usuario_Id')->constrained('SIS_Usuarios')->cascadeOnDelete();
            $table->foreignId('Papel_Id')->constrained('SIS_Papeis')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['Usuario_Id', 'Papel_Id']);
        });

        Schema::create('SIS_TenantUsuarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Tenant_Id')->constrained('SIS_Tenants')->cascadeOnDelete();
            $table->foreignId('Usuario_Id')->constrained('SIS_Usuarios')->cascadeOnDelete();
            $table->string('perfil', 40)->default('operador');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Tenant_Id', 'Usuario_Id']);
        });

        Schema::create('CAD_Empresas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Tenant_Id')->constrained('SIS_Tenants')->restrictOnDelete();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj', 14)->unique();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->string('regime_tributario', 30)->nullable();
            $table->string('ambiente_fiscal', 20)->default('homologacao');
            $table->unsignedInteger('limite_mensal_nfe')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->index(['Tenant_Id', 'ativo']);
        });

        Schema::create('CAD_Destinatarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->string('nome_razao_social');
            $table->string('cpf_cnpj', 14)->index();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'cpf_cnpj']);
        });

        Schema::create('CAD_Produtos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->string('codigo')->nullable();
            $table->string('descricao');
            $table->string('ncm', 8)->nullable();
            $table->string('cfop_padrao', 4)->nullable();
            $table->string('unidade_comercial', 6)->default('UN');
            $table->decimal('valor_unitario', 15, 4)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'codigo']);
        });

        Schema::create('FIS_CertificadosDigitais', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->string('nome');
            $table->string('tipo', 10)->default('A1');
            $table->string('caminho_arquivo');
            $table->text('senha_criptografada')->nullable();
            $table->date('validade')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('FIS_NfeDocumentos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->foreignId('Destinatario_Id')->nullable()->constrained('CAD_Destinatarios')->nullOnDelete();
            $table->unsignedInteger('numero')->nullable();
            $table->unsignedSmallInteger('serie')->nullable();
            $table->string('chave_acesso', 44)->nullable()->unique();
            $table->string('ambiente', 20)->default('homologacao');
            $table->string('status', 40)->default('rascunho');
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->string('protocolo_autorizacao')->nullable();
            $table->string('xml_autorizado_path')->nullable();
            $table->timestamp('emitida_at')->nullable();
            $table->timestamps();
            $table->index(['Empresa_Id', 'status']);
        });

        Schema::create('FIS_NfeEventos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->foreignId('NfeDocumento_Id')->nullable()->constrained('FIS_NfeDocumentos')->nullOnDelete();
            $table->foreignId('Usuario_Id')->nullable()->constrained('SIS_Usuarios')->nullOnDelete();
            $table->string('tipo', 40);
            $table->string('status', 40)->default('pendente');
            $table->string('protocolo')->nullable();
            $table->string('xml_envio_path')->nullable();
            $table->string('xml_retorno_path')->nullable();
            $table->text('mensagem')->nullable();
            $table->timestamps();
            $table->index(['Empresa_Id', 'tipo', 'status']);
        });

        Schema::create('LOG_Auditorias', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Tenant_Id')->nullable()->constrained('SIS_Tenants')->nullOnDelete();
            $table->foreignId('Empresa_Id')->nullable()->constrained('CAD_Empresas')->nullOnDelete();
            $table->foreignId('Usuario_Id')->nullable()->constrained('SIS_Usuarios')->nullOnDelete();
            $table->string('acao');
            $table->string('entidade_tipo')->nullable();
            $table->unsignedBigInteger('entidade_id')->nullable();
            $table->json('metadados')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['Tenant_Id', 'acao']);
            $table->index(['Empresa_Id', 'acao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('LOG_Auditorias');
        Schema::dropIfExists('FIS_NfeEventos');
        Schema::dropIfExists('FIS_NfeDocumentos');
        Schema::dropIfExists('FIS_CertificadosDigitais');
        Schema::dropIfExists('CAD_Produtos');
        Schema::dropIfExists('CAD_Destinatarios');
        Schema::dropIfExists('CAD_Empresas');
        Schema::dropIfExists('SIS_TenantUsuarios');
        Schema::dropIfExists('SIS_UsuarioPapeis');
        Schema::dropIfExists('SIS_PapelPermissoes');
        Schema::dropIfExists('SIS_Permissoes');
        Schema::dropIfExists('SIS_Papeis');
        Schema::dropIfExists('SIS_Tenants');
    }
};
