<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/Produtos/SalvarProdutoRequest.php
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

namespace App\Http\Requests\Produtos;

use Illuminate\Foundation\Http\FormRequest;

class SalvarProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $valorUnitario = str_replace(',', '.', (string) $this->input('valor_unitario'));

        $this->merge([
            'ncm' => preg_replace('/\D+/', '', (string) $this->input('ncm')),
            'cest' => preg_replace('/\D+/', '', (string) $this->input('cest')),
            'cfop_padrao' => preg_replace('/\D+/', '', (string) $this->input('cfop_padrao')),
            'unidade_comercial' => strtoupper((string) $this->input('unidade_comercial', 'UN')),
            'valor_unitario' => $valorUnitario === '' ? 0 : $valorUnitario,
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'codigo' => ['nullable', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:255'],
            'ncm' => ['nullable', 'digits:8'],
            'cest' => ['nullable', 'digits:7'],
            'cfop_padrao' => ['nullable', 'digits:4'],
            'unidade_comercial' => ['required', 'string', 'max:6'],
            'origem' => ['required', 'string', 'max:2'],
            'cst_csosn' => ['nullable', 'string', 'max:4'],
            'cst_pis' => ['nullable', 'string', 'max:2'],
            'cst_cofins' => ['nullable', 'string', 'max:2'],
            'valor_unitario' => ['required', 'numeric', 'min:0', 'max:99999999999.9999'],
            'ativo' => ['boolean'],
        ];
    }
}
