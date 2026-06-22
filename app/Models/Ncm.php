<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/Ncm.php
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

class Ncm extends Model
{
    protected $table = 'fis_ncms';

    protected $fillable = ['codigo', 'descricao', 'vigente_de', 'vigente_ate', 'ativo'];
}
