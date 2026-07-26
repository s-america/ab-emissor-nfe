<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarUsuarioAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $usuario = $this->route('usuario');
        $criando = $usuario === null;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('sis_usuarios', 'email')->ignore($usuario?->id)],
            'password' => [$criando ? 'required' : 'nullable', 'string', 'min:8', 'max:255'],
            'papel' => ['required', Rule::in(['super_admin_salta', 'admin_contabilidade', 'operador_contabilidade', 'cliente_emitente'])],
            'tenant_id' => ['nullable', 'integer', 'exists:sis_tenants,id'],
            'ativo' => ['boolean'],
        ];
    }
}
