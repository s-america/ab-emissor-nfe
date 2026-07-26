<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/Transportadoras/SalvarTransportadoraRequest.php
 *
 * @package ABEmissor\Requests
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario.
 *
 * @see /docs/10-fase-2-cadastros-fiscais.md
 * @deprecated false
 */

declare(strict_types=1);

namespace App\Http\Requests\Transportadoras;

use App\Models\Transportadora;
use App\Services\Empresas\EmpresaContextService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarTransportadoraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf_cnpj' => preg_replace('/\D+/', '', (string) $this->input('cpf_cnpj')),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $empresa = app(EmpresaContextService::class)->empresaAtual($this->user());
        $transportadoraId = $this->route('transportadora')?->getKey();

        return [
            'nome_razao_social' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => [
                'required',
                'digits_between:11,14',
                Rule::unique((new Transportadora())->getTable(), 'cpf_cnpj')
                    ->where('empresa_id', $empresa?->id)
                    ->ignore($transportadoraId),
            ],
            'inscricao_estadual' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'ativo' => ['boolean'],
        ];
    }
}
