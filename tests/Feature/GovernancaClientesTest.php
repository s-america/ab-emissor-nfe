<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Papel;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GovernancaClientesTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_administrador_pode_acessar_painel_sem_cliente_operacional(): void
    {
        $this->criarPapel('super_admin_salta', 'salta_admin');
        $usuario = User::query()->create([
            'nome' => 'Super Admin',
            'email' => 'super@example.com',
            'password' => 'password',
            'ativo' => true,
        ]);
        $usuario->papeis()->attach(Papel::query()->where('slug', 'super_admin_salta')->value('id'));

        $this->actingAs($usuario)->get('/admin/empresas')->assertOk();
        $this->actingAs($usuario)->get('/dashboard')->assertRedirect('/login');
    }

    public function test_login_do_super_administrador_redireciona_para_painel_separado(): void
    {
        [$usuario] = $this->superAdmin();

        $this->post('/login', ['email' => $usuario->email, 'password' => 'password'])
            ->assertRedirect('/admin/empresas');
    }

    public function test_super_administrador_cria_cliente_com_empresa_e_vinculo_contabil(): void
    {
        [$usuario, $contabilidade] = $this->superAdmin();

        $this->actingAs($usuario)->post('/admin/empresas', [
            'nome' => 'Cliente Novo',
            'slug' => 'cliente-novo',
            'tipo' => 'cliente',
            'contabilidade_tenant_id' => $contabilidade->id,
            'razao_social' => 'Cliente Novo LTDA',
            'cnpj' => '12345678000195',
            'ambiente_fiscal' => 'homologacao',
            'ativo' => '1',
        ])->assertRedirect('/admin/empresas');

        $this->assertDatabaseHas('sis_tenants', [
            'slug' => 'cliente-novo',
            'contabilidade_tenant_id' => $contabilidade->id,
        ]);
        $this->assertDatabaseHas('cad_empresas', ['cnpj' => '12345678000195']);
    }

    public function test_super_administrador_controla_usuarios_sem_vincular_super_admin_a_cliente(): void
    {
        [$usuario] = $this->superAdmin();

        $this->actingAs($usuario)->post('/admin/usuarios', [
            'nome' => 'Novo Super Admin',
            'email' => 'novo-super@example.com',
            'password' => 'senha-segura',
            'papel' => 'super_admin_salta',
            'ativo' => '1',
        ])->assertRedirect('/admin/usuarios');

        $novoUsuario = User::query()->where('email', 'novo-super@example.com')->firstOrFail();
        $this->assertDatabaseHas('sis_usuario_papeis', ['usuario_id' => $novoUsuario->id]);
        $this->assertDatabaseMissing('sis_tenant_usuarios', ['usuario_id' => $novoUsuario->id]);
    }

    public function test_cliente_com_movimento_e_desabilitado_em_vez_de_excluido(): void
    {
        [$usuario, $contabilidade] = $this->superAdmin();
        $cliente = Tenant::query()->create([
            'nome' => 'Cliente Com Movimento',
            'slug' => 'cliente-movimento',
            'tipo' => 'cliente',
            'contabilidade_tenant_id' => $contabilidade->id,
            'ativo' => true,
        ]);
        $empresa = Empresa::query()->create([
            'tenant_id' => $cliente->id,
            'razao_social' => 'Movimento LTDA',
            'cnpj' => '98765432000199',
            'ativo' => true,
        ]);
        $this->actingAs($usuario)->post('/admin/empresas', [
            'nome' => 'Auxiliar', 'slug' => 'auxiliar', 'tipo' => 'cliente', 'contabilidade_tenant_id' => $contabilidade->id,
            'razao_social' => 'Auxiliar LTDA', 'cnpj' => '11111111000191', 'ambiente_fiscal' => 'homologacao', 'ativo' => '1',
        ]);
        \Illuminate\Support\Facades\DB::table('fis_nfe_documentos')->insert(['empresa_id' => $empresa->id, 'ambiente' => 'homologacao', 'status' => 'rascunho', 'valor_total' => 0, 'created_at' => now(), 'updated_at' => now()]);

        $this->actingAs($usuario)->delete("/admin/empresas/{$cliente->id}")->assertRedirect('/admin/empresas');
        $this->assertDatabaseHas('sis_tenants', ['id' => $cliente->id, 'ativo' => false]);
    }

    public function test_contabilidade_nao_enxerga_cliente_de_outra_contabilidade(): void
    {
        $this->criarPapel('admin_contabilidade', 'contabilidade');
        $usuario = User::query()->create(['nome' => 'Contador', 'email' => 'contador@example.com', 'password' => 'password', 'ativo' => true]);
        $papelId = Papel::query()->where('slug', 'admin_contabilidade')->value('id');
        $usuario->papeis()->attach($papelId);
        $contabilidadeA = Tenant::query()->create(['nome' => 'Contabilidade A', 'slug' => 'cont-a', 'tipo' => 'contabilidade', 'ativo' => true]);
        $contabilidadeB = Tenant::query()->create(['nome' => 'Contabilidade B', 'slug' => 'cont-b', 'tipo' => 'contabilidade', 'ativo' => true]);
        $usuario->tenants()->attach($contabilidadeA->id, ['perfil' => 'admin_contabilidade', 'ativo' => true]);
        $clienteB = Tenant::query()->create(['nome' => 'Cliente B', 'slug' => 'cliente-b', 'tipo' => 'cliente', 'contabilidade_tenant_id' => $contabilidadeB->id, 'ativo' => true]);

        $this->actingAs($usuario)->get('/admin/empresas')->assertOk()->assertDontSee('Cliente B');
        $this->actingAs($usuario)->get("/admin/empresas/{$clienteB->id}/edit")->assertForbidden();
    }

    private function superAdmin(): array
    {
        $this->criarPapel('super_admin_salta', 'salta_admin');
        $usuario = User::query()->create(['nome' => 'Super Admin', 'email' => 'super-'.uniqid().'@example.com', 'password' => 'password', 'ativo' => true]);
        $usuario->papeis()->attach(Papel::query()->where('slug', 'super_admin_salta')->value('id'));
        $contabilidade = Tenant::query()->create(['nome' => 'AB Contabilidade', 'slug' => 'ab-contabilidade-'.uniqid(), 'tipo' => 'contabilidade', 'ativo' => true]);

        return [$usuario, $contabilidade];
    }

    private function criarPapel(string $slug, string $escopo): Papel
    {
        return Papel::query()->create(['nome' => $slug, 'slug' => $slug, 'escopo' => $escopo, 'ativo' => true]);
    }
}
