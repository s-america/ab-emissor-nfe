<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/Transportadora.php
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

class Transportadora extends Model
{
    protected $table = 'cad_transportadoras';

    protected $fillable = [
        'empresa_id',
        'nome_razao_social',
        'cpf_cnpj',
        'inscricao_estadual',
        'email',
        'telefone',
        'ativo',
    ];
}
