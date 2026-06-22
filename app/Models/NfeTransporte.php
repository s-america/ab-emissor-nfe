<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/NfeTransporte.php
 *
 * @package ABEmissor\Models
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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NfeTransporte extends Model
{
    protected $table = 'fis_nfe_transportes';

    protected $fillable = ['nfe_documento_id', 'transportadora_id', 'modalidade_frete', 'placa_veiculo', 'uf_veiculo'];
}
