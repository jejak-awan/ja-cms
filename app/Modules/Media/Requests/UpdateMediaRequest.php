<?php

namespace App\Modules\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alt_text' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'folder' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'alt_text.max' => 'Alt text maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'folder.max' => 'Nama folder maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'alt_text' => 'Alt Text',
            'description' => 'Deskripsi',
            'folder' => 'Folder',
        ];
    }
}
