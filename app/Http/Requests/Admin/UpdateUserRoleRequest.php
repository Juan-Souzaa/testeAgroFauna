<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('users.manage');
    }



    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::in(['admin', 'editor'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'role' => 'papel',
        ];
    }
}
