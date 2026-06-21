<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Model
 * FILE: app/Models/User.php
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
 * @see /docs/06-padroes-codigo.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    protected $table = 'SIS_Usuarios';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'ativo',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'SIS_TenantUsuarios', 'Usuario_Id', 'Tenant_Id')
            ->withPivot(['perfil', 'ativo'])
            ->withTimestamps();
    }

    public function papeis(): BelongsToMany
    {
        return $this->belongsToMany(Papel::class, 'SIS_UsuarioPapeis', 'Usuario_Id', 'Papel_Id')
            ->withTimestamps();
    }
}
