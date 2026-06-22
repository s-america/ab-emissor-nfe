<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: database/migrations/2026_06_21_000003_expand_fiscal_nfe_phase_2_1_tables.php
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
 * @see /docs/11-adequacao-formatos-atuais-nfe.md
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
        Schema::table('fis_nfe_documentos', function (Blueprint $table): void {
            $table->string('tipo_emissao', 40)->default('normal')->after('ambiente');
            $table->unsignedTinyInteger('tp_emis')->default(1)->after('tipo_emissao');
            $table->timestamp('dh_cont')->nullable()->after('tp_emis');
            $table->string('x_just')->nullable()->after('dh_cont');
            $table->string('protocolo_epec')->nullable()->after('x_just');
            $table->string('xml_contingencia_path')->nullable()->after('xml_autorizado_path');
            $table->timestamp('transmitida_pos_contingencia_at')->nullable()->after('xml_contingencia_path');
        });

        Schema::create('fis_nfe_itens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_documento_id')->constrained('fis_nfe_documentos')->cascadeOnDelete();
            $table->foreignId('produto_id')->nullable()->constrained('cad_produtos')->nullOnDelete();
            $table->unsignedInteger('numero_item');
            $table->string('codigo_produto')->nullable();
            $table->string('descricao_produto');
            $table->string('ncm', 8)->nullable();
            $table->string('cest', 7)->nullable();
            $table->string('cfop', 4)->nullable();
            $table->string('unidade_comercial', 6)->default('UN');
            $table->decimal('quantidade_comercial', 15, 4)->default(0);
            $table->decimal('valor_unitario_comercial', 15, 4)->default(0);
            $table->decimal('valor_total_bruto', 15, 2)->default(0);
            $table->decimal('valor_desconto', 15, 2)->default(0);
            $table->decimal('valor_frete', 15, 2)->default(0);
            $table->decimal('valor_seguro', 15, 2)->default(0);
            $table->decimal('valor_outras_despesas', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['nfe_documento_id', 'numero_item']);
        });

        Schema::create('fis_nfe_item_impostos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_item_id')->constrained('fis_nfe_itens')->cascadeOnDelete();
            $table->string('grupo', 20);
            $table->string('cst_csosn', 4)->nullable();
            $table->decimal('base_calculo', 15, 2)->default(0);
            $table->decimal('aliquota', 7, 4)->default(0);
            $table->decimal('valor', 15, 2)->default(0);
            $table->json('detalhes')->nullable();
            $table->timestamps();
            $table->index(['nfe_item_id', 'grupo']);
        });

        Schema::create('fis_nfe_pagamentos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_documento_id')->constrained('fis_nfe_documentos')->cascadeOnDelete();
            $table->string('meio_pagamento', 20);
            $table->decimal('valor_pagamento', 15, 2)->default(0);
            $table->string('bandeira_cartao', 20)->nullable();
            $table->string('cnpj_credenciadora', 14)->nullable();
            $table->timestamps();
        });

        Schema::create('fis_nfe_transportes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_documento_id')->constrained('fis_nfe_documentos')->cascadeOnDelete();
            $table->foreignId('transportadora_id')->nullable()->constrained('cad_transportadoras')->nullOnDelete();
            $table->string('modalidade_frete', 20)->default('sem_frete');
            $table->string('placa_veiculo', 10)->nullable();
            $table->char('uf_veiculo', 2)->nullable();
            $table->timestamps();
        });

        Schema::create('fis_nfe_volumes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_transporte_id')->constrained('fis_nfe_transportes')->cascadeOnDelete();
            $table->unsignedInteger('quantidade')->nullable();
            $table->string('especie')->nullable();
            $table->string('marca')->nullable();
            $table->string('numeracao')->nullable();
            $table->decimal('peso_liquido', 15, 3)->nullable();
            $table->decimal('peso_bruto', 15, 3)->nullable();
            $table->timestamps();
        });

        Schema::create('fis_nfe_referenciadas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_documento_id')->constrained('fis_nfe_documentos')->cascadeOnDelete();
            $table->string('tipo', 20)->default('nfe');
            $table->string('chave_acesso', 44)->nullable();
            $table->string('modelo', 2)->nullable();
            $table->string('serie', 3)->nullable();
            $table->string('numero', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('fis_nfe_tentativas_transmissao', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('nfe_documento_id')->constrained('fis_nfe_documentos')->cascadeOnDelete();
            $table->string('ambiente', 20);
            $table->string('tipo_emissao', 40);
            $table->string('status', 40)->default('pendente');
            $table->unsignedInteger('codigo_retorno')->nullable();
            $table->text('mensagem_retorno')->nullable();
            $table->string('recibo')->nullable();
            $table->string('protocolo')->nullable();
            $table->string('xml_envio_path')->nullable();
            $table->string('xml_retorno_path')->nullable();
            $table->timestamp('iniciada_at')->nullable();
            $table->timestamp('finalizada_at')->nullable();
            $table->timestamps();
            $table->index(['nfe_documento_id', 'status']);
        });

        Schema::create('fis_nfe_inutilizacoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cad_empresas')->restrictOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('sis_usuarios')->nullOnDelete();
            $table->string('ambiente', 20)->default('homologacao');
            $table->unsignedSmallInteger('serie');
            $table->unsignedInteger('numero_inicial');
            $table->unsignedInteger('numero_final');
            $table->string('justificativa');
            $table->string('status', 40)->default('pendente');
            $table->string('protocolo')->nullable();
            $table->string('xml_envio_path')->nullable();
            $table->string('xml_retorno_path')->nullable();
            $table->timestamps();
            $table->index(['empresa_id', 'ambiente', 'serie']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fis_nfe_inutilizacoes');
        Schema::dropIfExists('fis_nfe_tentativas_transmissao');
        Schema::dropIfExists('fis_nfe_referenciadas');
        Schema::dropIfExists('fis_nfe_volumes');
        Schema::dropIfExists('fis_nfe_transportes');
        Schema::dropIfExists('fis_nfe_pagamentos');
        Schema::dropIfExists('fis_nfe_item_impostos');
        Schema::dropIfExists('fis_nfe_itens');

        Schema::table('fis_nfe_documentos', function (Blueprint $table): void {
            $table->dropColumn([
                'tipo_emissao',
                'tp_emis',
                'dh_cont',
                'x_just',
                'protocolo_epec',
                'xml_contingencia_path',
                'transmitida_pos_contingencia_at',
            ]);
        });
    }
};
