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
        Schema::create('sis_tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('tipo', 30)->default('cliente');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('sis_papeis', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('sis_permissoes', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('sis_papel_permissoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('papel_id')->constrained('sis_papeis')->cascadeOnDelete();
            $table->foreignId('permissao_id')->constrained('sis_permissoes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['papel_id', 'permissao_id']);
        });

        Schema::create('sis_usuario_papeis', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('usuario_id')->constrained('sis_usuarios')->cascadeOnDelete();
            $table->foreignId('papel_id')->constrained('sis_papeis')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['usuario_id', 'papel_id']);
        });

        Schema::create('sis_tenant_usuarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('sis_tenants')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('sis_usuarios')->cascadeOnDelete();
            $table->string('perfil', 40)->default('operador');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['tenant_id', 'usuario_id']);
        });

        Schema::create('cad_empresas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('sis_tenants')->restrictOnDelete();
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
            $table->index(['tenant_id', 'ativo']);
        });

        Schema::create('cad_destinatarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->string('nome_razao_social');
            $table->string('cpf_cnpj', 14)->index();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['empresa_id', 'cpf_cnpj']);
        });

        Schema::create('cad_produtos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->string('codigo')->nullable();
            $table->string('descricao');
            $table->string('ncm', 8)->nullable();
            $table->string('cfop_padrao', 4)->nullable();
            $table->string('unidade_comercial', 6)->default('UN');
            $table->decimal('valor_unitario', 15, 4)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['empresa_id', 'codigo']);
        });

        Schema::create('fis_certificados_digitais', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->string('nome');
            $table->string('tipo', 10)->default('A1');
            $table->string('caminho_arquivo');
            $table->text('senha_criptografada')->nullable();
            $table->date('validade')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('fis_nfe_documentos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->foreignId('destinatario_id')->nullable()->constrained('cad_destinatarios')->nullOnDelete();
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
            $table->index(['empresa_id', 'status']);
        });

        Schema::create('fis_nfe_eventos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->foreignId('nfe_documento_id')->nullable()->constrained('fis_nfe_documentos')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('sis_usuarios')->nullOnDelete();
            $table->string('tipo', 40);
            $table->string('status', 40)->default('pendente');
            $table->string('protocolo')->nullable();
            $table->string('xml_envio_path')->nullable();
            $table->string('xml_retorno_path')->nullable();
            $table->text('mensagem')->nullable();
            $table->timestamps();
            $table->index(['empresa_id', 'tipo', 'status']);
        });

        Schema::create('log_auditorias', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('sis_tenants')->nullOnDelete();
            $table->foreignId('empresa_id')->nullable()->constrained('cad_empresas')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('sis_usuarios')->nullOnDelete();
            $table->string('acao');
            $table->string('entidade_tipo')->nullable();
            $table->unsignedBigInteger('entidade_id')->nullable();
            $table->json('metadados')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'acao']);
            $table->index(['empresa_id', 'acao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_auditorias');
        Schema::dropIfExists('fis_nfe_eventos');
        Schema::dropIfExists('fis_nfe_documentos');
        Schema::dropIfExists('fis_certificados_digitais');
        Schema::dropIfExists('cad_produtos');
        Schema::dropIfExists('cad_destinatarios');
        Schema::dropIfExists('cad_empresas');
        Schema::dropIfExists('sis_tenant_usuarios');
        Schema::dropIfExists('sis_usuario_papeis');
        Schema::dropIfExists('sis_papel_permissoes');
        Schema::dropIfExists('sis_permissoes');
        Schema::dropIfExists('sis_papeis');
        Schema::dropIfExists('sis_tenants');
    }
};

