<?php
/**
 * PROJECT: AB Emissor
 * TYPE: Request
 * FILE: app/Http/Requests/Ncms/SalvarNcmRequest.php
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

namespace App\Http\Requests\Ncms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarNcmRequest extends FormRequest
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
        $ncmId = $this->route('ncm')?->getKey();

        return [
            'codigo' => ['required', 'digits:8', Rule::unique('fis_ncms', 'codigo')->ignore($ncmId)],
            'descricao' => ['required', 'string', 'max:255'],
            'vigente_de' => ['nullable', 'date'],
            'vigente_ate' => ['nullable', 'date', 'after_or_equal:vigente_de'],
            'ativo' => ['boolean'],
        ];
    }
}
