<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/Permissao.php
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
 * @see /docs/arquitetura-ab-emissor-nfe-v1.0.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    protected $table = 'sis_permissoes';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
    ];
}
