<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('books.create');
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('publicado_em') === '' || $this->input('publicado_em') === null) {
            $this->merge(['publicado_em' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:32', 'unique:livros,isbn'],
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
