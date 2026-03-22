<?php

namespace App\Http\Requests;

use App\Models\Livro;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListarLivrosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Livro::class);
    }

    protected function prepareForValidation(): void
    {
        $permitidos = config('livros.per_page.options');
        $padrao = (int) config('livros.per_page.default');
        $bruto = $this->query('per_page');

        if ($bruto === null || $bruto === '') {
            $this->merge(['per_page' => $padrao]);

            return;
        }

        $v = (int) $bruto;
        if (! in_array($v, $permitidos, true)) {
            $this->merge(['per_page' => $padrao]);
        }
    }

    public function rules(): array
    {
        return [
            'per_page' => ['required', 'integer', Rule::in(config('livros.per_page.options'))],
        ];
    }

    public function itensPorPagina(): int
    {
        return (int) $this->validated('per_page');
    }
}
