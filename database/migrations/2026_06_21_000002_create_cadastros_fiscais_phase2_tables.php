<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: database/migrations/2026_06_21_000002_create_cadastros_fiscais_phase2_tables.php
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
        Schema::table('CAD_Destinatarios', function (Blueprint $table): void {
            $table->string('indicador_ie', 20)->default('nao_contribuinte')->after('inscricao_estadual');
            $table->string('logradouro')->nullable()->after('telefone');
            $table->string('numero', 20)->nullable()->after('logradouro');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->nullable()->after('complemento');
            $table->string('codigo_municipio_ibge', 7)->nullable()->after('bairro');
            $table->string('municipio')->nullable()->after('codigo_municipio_ibge');
            $table->char('uf', 2)->nullable()->after('municipio');
            $table->string('cep', 8)->nullable()->after('uf');
        });

        Schema::table('CAD_Produtos', function (Blueprint $table): void {
            $table->string('cest', 7)->nullable()->after('ncm');
            $table->string('origem', 2)->default('0')->after('unidade_comercial');
            $table->string('cst_csosn', 4)->nullable()->after('origem');
            $table->string('cst_pis', 2)->nullable()->after('cst_csosn');
            $table->string('cst_cofins', 2)->nullable()->after('cst_pis');
        });

        Schema::create('CAD_Transportadoras', function (Blueprint $table): void {
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

        Schema::create('CAD_NaturezasOperacao', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('Empresa_Id')->constrained('CAD_Empresas')->restrictOnDelete();
            $table->string('descricao');
            $table->string('tipo_operacao', 20)->default('saida');
            $table->string('cfop_padrao', 4)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['Empresa_Id', 'descricao']);
        });

        Schema::create('FIS_Cfops', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 4)->unique();
            $table->string('descricao');
            $table->string('tipo_operacao', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('FIS_Ncms', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 8)->unique();
            $table->string('descricao');
            $table->date('vigente_de')->nullable();
            $table->date('vigente_ate')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('FIS_Ncms');
        Schema::dropIfExists('FIS_Cfops');
        Schema::dropIfExists('CAD_NaturezasOperacao');
        Schema::dropIfExists('CAD_Transportadoras');

        Schema::table('CAD_Produtos', function (Blueprint $table): void {
            $table->dropColumn(['cest', 'origem', 'cst_csosn', 'cst_pis', 'cst_cofins']);
        });

        Schema::table('CAD_Destinatarios', function (Blueprint $table): void {
            $table->dropColumn([
                'indicador_ie',
                'logradouro',
                'numero',
                'complemento',
                'bairro',
                'codigo_municipio_ibge',
                'municipio',
                'uf',
                'cep',
            ]);
        });
    }
};
