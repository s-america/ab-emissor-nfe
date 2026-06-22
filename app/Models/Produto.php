<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/Produto.php
 *
 * @package ABEmissor\Models
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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produto extends Model
{
    protected $table = 'CAD_Produtos';

    protected $fillable = [
        'Empresa_Id',
        'codigo',
        'descricao',
        'ncm',
        'cest',
        'cfop_padrao',
        'unidade_comercial',
        'origem',
        'cst_csosn',
        'cst_pis',
        'cst_cofins',
        'valor_unitario',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'valor_unitario' => 'decimal:4',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'Empresa_Id');
    }
}
