<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Core
 * FILE: tests/Feature/Fase2CadastrosFiscaisTest.php
 *
 * @package ABEmissor\Core
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cfop;
use App\Models\Empresa;
use App\Models\Ncm;
use App\Models\Tenant;
use App\Models\Transportadora;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Fase2CadastrosFiscaisTest extends TestCase
{
    use RefreshDatabase;

    public function test_telas_dos_cadastros_fiscais_exigem_autenticacao(): void
    {
        foreach (['/transportadoras', '/naturezas-operacao', '/cfops', '/ncms'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_administrador_visualiza_todas_as_telas_da_fase_2(): void
    {
        [$usuario] = $this->criarContexto();

        foreach ([
            '/transportadoras',
            '/transportadoras/create',
            '/naturezas-operacao',
            '/naturezas-operacao/create',
            '/cfops',
            '/cfops/create',
            '/ncms',
            '/ncms/create',
            '/produtos/create',
        ] as $url) {
            $this->actingAs($usuario)->get($url)->assertOk();
        }
    }

    public function test_administrador_cadastra_cfop_e_ncm_com_auditoria(): void
    {
        [$usuario] = $this->criarContexto();

        $this->actingAs($usuario)->post('/cfops', [
            'codigo' => '5102',
            'descricao' => 'Venda de mercadoria adquirida de terceiros',
            'tipo_operacao' => 'saida',
            'ativo' => '1',
        ])->assertRedirect('/cfops');

        $this->actingAs($usuario)->post('/ncms', [
            'codigo' => '49019900',
            'descricao' => 'Outros livros e impressos',
            'vigente_de' => '2026-01-01',
            'ativo' => '1',
        ])->assertRedirect('/ncms');

        $this->assertDatabaseHas('fis_cfops', ['codigo' => '5102', 'tipo_operacao' => 'saida']);
        $this->assertDatabaseHas('fis_ncms', ['codigo' => '49019900']);
        $this->assertDatabaseHas('log_auditorias', ['acao' => 'cfop.criado']);
        $this->assertDatabaseHas('log_auditorias', ['acao' => 'ncm.criado']);
    }

    public function test_operador_nao_pode_alterar_catalogos_fiscais_globais(): void
    {
        [$usuario] = $this->criarContexto('operador');

        $this->actingAs($usuario)->post('/cfops', [
            'codigo' => '5102',
            'descricao' => 'Venda',
            'tipo_operacao' => 'saida',
            'ativo' => '1',
        ])->assertForbidden();

        $this->assertDatabaseMissing('fis_cfops', ['codigo' => '5102']);
    }

    public function test_cadastra_transportadora_somente_na_empresa_atual(): void
    {
        [$usuario, $empresa] = $this->criarContexto();

        $this->actingAs($usuario)->post('/transportadoras', [
            'nome_razao_social' => 'Transportes Seguros LTDA',
            'cpf_cnpj' => '12.345.678/0001-90',
            'email' => 'contato@transportes.test',
            'ativo' => '1',
        ])->assertRedirect('/transportadoras');

        $this->assertDatabaseHas('cad_transportadoras', [
            'empresa_id' => $empresa->id,
            'cpf_cnpj' => '12345678000190',
        ]);
        $this->assertDatabaseHas('log_auditorias', ['acao' => 'transportadora.criado']);
    }

    public function test_bloqueia_edicao_de_transportadora_de_outra_empresa(): void
    {
        [$usuario] = $this->criarContexto();
        [, $outraEmpresa] = $this->criarContexto('admin_contabilidade', 'outra');
        $transportadora = Transportadora::query()->create([
            'empresa_id' => $outraEmpresa->id,
            'nome_razao_social' => 'Transportadora Externa',
            'cpf_cnpj' => '98765432000199',
        ]);

        $this->actingAs($usuario)->get("/transportadoras/{$transportadora->id}/edit")->assertForbidden();
    }

    public function test_natureza_exige_cfop_ativo_compativel_com_tipo_da_operacao(): void
    {
        [$usuario, $empresa] = $this->criarContexto();
        Cfop::query()->create([
            'codigo' => '1102',
            'descricao' => 'Compra para comercializacao',
            'tipo_operacao' => 'entrada',
            'ativo' => true,
        ]);

        $this->actingAs($usuario)->post('/naturezas-operacao', [
            'descricao' => 'Venda de mercadoria',
            'tipo_operacao' => 'saida',
            'cfop_padrao' => '1102',
            'ativo' => '1',
        ])->assertSessionHasErrors('cfop_padrao');

        $this->assertDatabaseMissing('cad_naturezas_operacao', [
            'empresa_id' => $empresa->id,
            'descricao' => 'Venda de mercadoria',
        ]);
    }

    public function test_produto_exige_ncm_e_cfop_ativos_quando_informados(): void
    {
        [$usuario, $empresa] = $this->criarContexto();

        $this->actingAs($usuario)->post('/produtos', [
            'codigo' => 'P-001',
            'descricao' => 'Produto fiscal',
            'ncm' => '49019900',
            'cfop_padrao' => '5102',
            'unidade_comercial' => 'UN',
            'origem' => '0',
            'valor_unitario' => '10,00',
            'ativo' => '1',
        ])->assertSessionHasErrors(['ncm', 'cfop_padrao']);

        Ncm::query()->create(['codigo' => '49019900', 'descricao' => 'Outros impressos', 'ativo' => true]);
        Cfop::query()->create(['codigo' => '5102', 'descricao' => 'Venda', 'tipo_operacao' => 'saida', 'ativo' => true]);

        $this->actingAs($usuario)->post('/produtos', [
            'codigo' => 'P-001',
            'descricao' => 'Produto fiscal',
            'ncm' => '49019900',
            'cfop_padrao' => '5102',
            'unidade_comercial' => 'UN',
            'origem' => '0',
            'valor_unitario' => '10,00',
            'ativo' => '1',
        ])->assertRedirect('/produtos');

        $this->assertDatabaseHas('cad_produtos', [
            'empresa_id' => $empresa->id,
            'codigo' => 'P-001',
            'ncm' => '49019900',
            'cfop_padrao' => '5102',
        ]);
    }

    /**
     * @return array{User, Empresa}
     */
    private function criarContexto(string $perfil = 'admin_contabilidade', string $sufixo = 'principal'): array
    {
        $usuario = User::query()->create([
            'nome' => 'Usuario '.$sufixo,
            'email' => "usuario-{$sufixo}@example.com",
            'password' => 'password',
            'ativo' => true,
        ]);
        $tenant = Tenant::query()->create([
            'nome' => 'Tenant '.$sufixo,
            'slug' => 'tenant-'.$sufixo,
        ]);
        $empresa = Empresa::query()->create([
            'tenant_id' => $tenant->id,
            'razao_social' => 'Empresa '.$sufixo,
            'cnpj' => str_pad((string) $tenant->id, 14, '0', STR_PAD_LEFT),
            'ativo' => true,
        ]);
        $usuario->tenants()->attach($tenant->id, ['perfil' => $perfil, 'ativo' => true]);

        return [$usuario, $empresa];
    }
}
