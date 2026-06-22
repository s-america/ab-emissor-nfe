<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/Papel.php
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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Papel extends Model
{
    protected $table = 'sis_papeis';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'ativo',
    ];

    public function permissoes(): BelongsToMany
    {
        return $this->belongsToMany(Permissao::class, 'sis_papel_permissoes', 'papel_id', 'permissao_id')
            ->withTimestamps();
    }
}

