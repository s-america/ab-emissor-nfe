<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Action
 * FILE: app/Actions/Produtos/SalvarProdutoAction.php
 *
 * @package ABEmissor\Actions
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licencas.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Actions\Produtos;

use App\Models\Empresa;
use App\Models\Produto;
use App\Models\User;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\Request;

class SalvarProdutoAction
{
    public function __construct(private readonly AuditoriaService $auditoriaService)
    {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function criar(Empresa $empresa, User $usuario, array $dados, Request $request): Produto
    {
        $produto = Produto::query()->create($dados + ['Empresa_Id' => $empresa->id]);

        $this->registrarAuditoria('produto.criado', $empresa, $usuario, $produto, $request);

        return $produto;
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function atualizar(Empresa $empresa, User $usuario, Produto $produto, array $dados, Request $request): Produto
    {
        $produto->fill($dados);
        $produto->save();

        $this->registrarAuditoria('produto.atualizado', $empresa, $usuario, $produto, $request);

        return $produto;
    }

    private function registrarAuditoria(string $acao, Empresa $empresa, User $usuario, Produto $produto, Request $request): void
    {
        $this->auditoriaService->registrar(
            acao: $acao,
            usuarioId: (int) $usuario->id,
            tenantId: (int) $empresa->Tenant_Id,
            empresaId: (int) $empresa->id,
            entidadeTipo: Produto::class,
            entidadeId: (int) $produto->id,
            request: $request,
        );
    }
}
