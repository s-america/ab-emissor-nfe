<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/Destinatarios/SalvarDestinatarioRequest.php
 *
 * @package ABEmissor\Requests
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

namespace App\Http\Requests\Destinatarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarDestinatarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf_cnpj' => preg_replace('/\D+/', '', (string) $this->input('cpf_cnpj')),
            'cep' => preg_replace('/\D+/', '', (string) $this->input('cep')),
            'uf' => strtoupper((string) $this->input('uf')),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nome_razao_social' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => ['required', 'digits_between:11,14'],
            'inscricao_estadual' => ['nullable', 'string', 'max:20'],
            'indicador_ie' => ['required', Rule::in(['contribuinte', 'isento', 'nao_contribuinte'])],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'codigo_municipio_ibge' => ['nullable', 'digits:7'],
            'municipio' => ['nullable', 'string', 'max:255'],
            'uf' => ['nullable', 'string', 'size:2'],
            'cep' => ['nullable', 'digits:8'],
            'ativo' => ['boolean'],
        ];
    }
}
