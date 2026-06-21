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
        Schema::create('ABE_Empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj', 14)->unique();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->string('regime_tributario', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('ABE_EmpresaUsuarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->cascadeOnDelete();
            $table->foreignId('Usuario_Id')->constrained('ABE_Usuarios')->cascadeOnDelete();
            $table->string('perfil', 40)->default('operador');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'Usuario_Id']);
        });

        Schema::create('ABE_ClientesDestinatarios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->restrictOnDelete();
            $table->string('nome_razao_social');
            $table->string('cpf_cnpj', 14)->index();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('codigo_municipio_ibge', 7)->nullable();
            $table->string('municipio')->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('cep', 8)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'cpf_cnpj']);
        });

        Schema::create('ABE_Produtos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->restrictOnDelete();
            $table->string('codigo')->nullable();
            $table->string('descricao');
            $table->string('ncm', 8)->nullable();
            $table->string('cest', 7)->nullable();
            $table->string('cfop_padrao', 4)->nullable();
            $table->string('unidade_comercial', 6)->default('UN');
            $table->decimal('valor_unitario', 15, 4)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'codigo']);
        });

        Schema::create('ABE_CertificadosDigitais', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->restrictOnDelete();
            $table->string('nome');
            $table->string('tipo', 10)->default('A1');
            $table->string('caminho_arquivo');
            $table->text('senha_criptografada')->nullable();
            $table->date('validade')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('ABE_Nfes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->restrictOnDelete();
            $table->foreignId('ClienteDestinatario_Id')->nullable()->constrained('ABE_ClientesDestinatarios')->nullOnDelete();
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

        Schema::create('ABE_EventosFiscais', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('ABE_Empresas')->restrictOnDelete();
            $table->foreignId('Nfe_Id')->nullable()->constrained('ABE_Nfes')->nullOnDelete();
            $table->foreignId('Usuario_Id')->nullable()->constrained('ABE_Usuarios')->nullOnDelete();
            $table->string('tipo', 40);
            $table->string('status', 40)->default('pendente');
            $table->string('protocolo')->nullable();
            $table->string('xml_envio_path')->nullable();
            $table->string('xml_retorno_path')->nullable();
            $table->text('mensagem')->nullable();
            $table->timestamps();
            $table->index(['Empresa_Id', 'tipo', 'status']);
        });

        Schema::create('ABE_Auditorias', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->nullable()->constrained('ABE_Empresas')->nullOnDelete();
            $table->foreignId('Usuario_Id')->nullable()->constrained('ABE_Usuarios')->nullOnDelete();
            $table->string('acao');
            $table->string('entidade_tipo')->nullable();
            $table->unsignedBigInteger('entidade_id')->nullable();
            $table->json('metadados')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['Empresa_Id', 'acao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ABE_Auditorias');
        Schema::dropIfExists('ABE_EventosFiscais');
        Schema::dropIfExists('ABE_Nfes');
        Schema::dropIfExists('ABE_CertificadosDigitais');
        Schema::dropIfExists('ABE_Produtos');
        Schema::dropIfExists('ABE_ClientesDestinatarios');
        Schema::dropIfExists('ABE_EmpresaUsuarios');
        Schema::dropIfExists('ABE_Empresas');
    }
};
