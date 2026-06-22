<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: tests/Feature/Etapa21EstruturaFiscalTest.php
 *
 * @package ABEmissor\Core
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/11-adequacao-formatos-atuais-nfe.md
 * @deprecated false
 */

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Destinatario;
use App\Models\Empresa;
use App\Models\NfeDocumento;
use App\Models\Produto;
use App\Models\Tenant;
use App\Models\User;
use App\Policies\EmpresaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Etapa21EstruturaFiscalTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_criam_tabelas_principais_em_snake_case(): void
    {
        $tabelas = [
            'sis_usuarios',
            'sis_tenants',
            'cad_empresas',
            'cad_destinatarios',
            'cad_produtos',
            'fis_nfe_documentos',
            'fis_nfe_itens',
            'fis_nfe_item_impostos',
            'fis_nfe_pagamentos',
            'fis_nfe_transportes',
            'fis_nfe_volumes',
            'fis_nfe_referenciadas',
            'fis_nfe_tentativas_transmissao',
            'fis_nfe_inutilizacoes',
            'log_auditorias',
        ];

        foreach ($tabelas as $tabela) {
            $this->assertTrue(Schema::hasTable($tabela), "Tabela {$tabela} nao foi criada.");
        }
    }

    public function test_models_apontam_para_tabelas_corretas(): void
    {
        $this->assertSame('sis_tenants', (new Tenant())->getTable());
        $this->assertSame('cad_empresas', (new Empresa())->getTable());
        $this->assertSame('cad_destinatarios', (new Destinatario())->getTable());
        $this->assertSame('cad_produtos', (new Produto())->getTable());
        $this->assertSame('fis_nfe_documentos', (new NfeDocumento())->getTable());
    }

    public function test_empresa_pertence_a_tenant(): void
    {
        $tenant = Tenant::query()->create(['nome' => 'Tenant A', 'slug' => 'tenant-a']);
        $empresa = Empresa::query()->create([
            'tenant_id' => $tenant->id,
            'razao_social' => 'Empresa A',
            'cnpj' => '11111111000191',
        ]);

        $this->assertTrue($empresa->tenant->is($tenant));
    }

    public function test_cadastros_fiscais_pertencem_a_empresa(): void
    {
        $empresa = $this->criarEmpresa('Tenant Cadastros', 'tenant-cadastros', '22222222000191');

        $destinatario = Destinatario::query()->create([
            'empresa_id' => $empresa->id,
            'nome_razao_social' => 'Destinatario Teste',
            'cpf_cnpj' => '12345678901',
            'indicador_ie' => 'nao_contribuinte',
        ]);

        $produto = Produto::query()->create([
            'empresa_id' => $empresa->id,
            'descricao' => 'Produto Teste',
            'unidade_comercial' => 'UN',
            'origem' => '0',
            'valor_unitario' => 10,
        ]);

        $this->assertTrue($destinatario->empresa->is($empresa));
        $this->assertTrue($produto->empresa->is($empresa));
    }

    public function test_policy_impede_acesso_cruzado_entre_empresas(): void
    {
        $usuario = User::query()->create([
            'nome' => 'Usuario Teste',
            'email' => 'usuario-etapa21@example.com',
            'password' => 'password',
        ]);

        $empresaPermitida = $this->criarEmpresa('Tenant Permitido', 'tenant-permitido', '33333333000191');
        $empresaBloqueada = $this->criarEmpresa('Tenant Bloqueado', 'tenant-bloqueado', '44444444000191');

        $usuario->tenants()->attach($empresaPermitida->tenant_id, [
            'perfil' => 'operador',
            'ativo' => true,
        ]);

        $policy = new EmpresaPolicy();

        $this->assertTrue($policy->view($usuario, $empresaPermitida));
        $this->assertFalse($policy->view($usuario, $empresaBloqueada));
    }

    private function criarEmpresa(string $tenantNome, string $tenantSlug, string $cnpj): Empresa
    {
        $tenant = Tenant::query()->create([
            'nome' => $tenantNome,
            'slug' => $tenantSlug,
        ]);

        return Empresa::query()->create([
            'tenant_id' => $tenant->id,
            'razao_social' => $tenantNome.' LTDA',
            'cnpj' => $cnpj,
        ]);
    }
}
