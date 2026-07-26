<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarClienteAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cnpj' => preg_replace('/\D+/', '', (string) $this->input('cnpj')),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $cliente = $this->route('empresa');

        return [
            'nome' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique('sis_tenants', 'slug')->ignore($cliente?->id)],
            'tipo' => ['required', Rule::in(['cliente', 'contabilidade'])],
            'contabilidade_tenant_id' => [
                'nullable',
                'integer',
                Rule::exists('sis_tenants', 'id')->where('tipo', 'contabilidade')->where('ativo', true),
            ],
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'digits:14', Rule::unique('cad_empresas', 'cnpj')->ignore($cliente?->empresas()->value('id'))],
            'inscricao_estadual' => ['nullable', 'string', 'max:20'],
            'ambiente_fiscal' => ['required', Rule::in(['homologacao', 'producao'])],
            'ativo' => ['boolean'],
        ];
    }
}
