<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('livro'));
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('publicado_em') === '' || $this->input('publicado_em') === null) {
            $this->merge(['publicado_em' => null]);
        }
    }

    public function rules(): array
    {
        $livro = $this->route('livro');

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:32', Rule::unique('livros', 'isbn')->ignore($livro->id)],
            'preco' => ['required', 'numeric', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'publicado_em' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'autor' => 'autor',
            'isbn' => 'ISBN',
            'preco' => 'preço',
            'categoria_id' => 'categoria',
            'publicado_em' => 'data de publicação',
        ];
    }
}
