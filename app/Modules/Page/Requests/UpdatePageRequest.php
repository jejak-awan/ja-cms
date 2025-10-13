<?php

namespace App\Modules\Page\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page') ?? $this->route('id');
        
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($pageId), 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:pages,id', Rule::notIn([$pageId])],
            'template' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'order' => ['nullable', 'integer', 'min:0'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul halaman harus diisi.',
            'title.max' => 'Judul halaman maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'content.required' => 'Konten halaman harus diisi.',
            'parent_id.exists' => 'Halaman parent tidak ditemukan.',
            'parent_id.not_in' => 'Halaman tidak boleh menjadi parent dari dirinya sendiri.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'slug' => 'Slug',
            'excerpt' => 'Ringkasan',
            'content' => 'Konten',
            'parent_id' => 'Parent',
            'template' => 'Template',
            'status' => 'Status',
            'order' => 'Urutan',
            'featured_image' => 'Gambar Unggulan',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'published_at' => 'Tanggal Publikasi',
        ];
    }
}
