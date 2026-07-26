<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/NaturezasOperacao/SalvarNaturezaOperacaoRequest.php
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

namespace App\Http\Requests\NaturezasOperacao;

use App\Models\Cfop;
use App\Models\NaturezaOperacao;
use App\Services\Empresas\EmpresaContextService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarNaturezaOperacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cfop_padrao' => preg_replace('/\D+/', '', (string) $this->input('cfop_padrao')),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $empresa = app(EmpresaContextService::class)->empresaAtual($this->user());
        $naturezaId = $this->route('naturezaOperacao')?->getKey();

        return [
            'descricao' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new NaturezaOperacao())->getTable(), 'descricao')
                    ->where('empresa_id', $empresa?->id)
                    ->ignore($naturezaId),
            ],
            'tipo_operacao' => ['required', Rule::in(['entrada', 'saida'])],
            'cfop_padrao' => [
                'nullable',
                'digits:4',
                Rule::exists((new Cfop())->getTable(), 'codigo')->where('ativo', true),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $entrada = in_array(substr((string) $value, 0, 1), ['1', '2', '3'], true);
                    $tipoEsperado = $entrada ? 'entrada' : 'saida';

                    if ((string) $this->input('tipo_operacao') !== $tipoEsperado) {
                        $fail('O CFOP informado nao corresponde ao tipo de operacao.');
                    }
                },
            ],
            'ativo' => ['boolean'],
        ];
    }
}
