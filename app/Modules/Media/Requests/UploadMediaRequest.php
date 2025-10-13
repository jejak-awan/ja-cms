<?php

namespace App\Modules\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB max
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            ],
            'folder' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File harus dipilih.',
            'file.file' => 'File tidak valid.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'file.mimes' => 'Tipe file tidak didukung. Format yang didukung: JPG, PNG, GIF, WebP, PDF, DOC, XLS, PPT, TXT, ZIP, RAR.',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'File',
            'folder' => 'Folder',
            'alt_text' => 'Alt Text',
            'description' => 'Deskripsi',
        ];
    }
}
