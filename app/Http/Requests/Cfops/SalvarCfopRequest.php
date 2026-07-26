<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/Cfops/SalvarCfopRequest.php
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

namespace App\Http\Requests\Cfops;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarCfopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'codigo' => preg_replace('/\D+/', '', (string) $this->input('codigo')),
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $cfopId = $this->route('cfop')?->getKey();

        return [
            'codigo' => [
                'required',
                'digits:4',
                Rule::unique('fis_cfops', 'codigo')->ignore($cfopId),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! in_array(substr((string) $value, 0, 1), ['1', '2', '3', '5', '6', '7'], true)) {
                        $fail('O CFOP deve iniciar por 1, 2, 3, 5, 6 ou 7.');
                    }
                },
            ],
            'descricao' => ['required', 'string', 'max:255'],
            'tipo_operacao' => ['required', Rule::in(['entrada', 'saida'])],
            'ativo' => ['boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $codigo = (string) $this->input('codigo');
            $tipoCalculado = in_array(substr($codigo, 0, 1), ['1', '2', '3'], true) ? 'entrada' : 'saida';

            if (strlen($codigo) === 4 && $this->input('tipo_operacao') !== $tipoCalculado) {
                $validator->errors()->add('tipo_operacao', 'O tipo da operacao nao corresponde ao codigo CFOP.');
            }
        });
    }
}
