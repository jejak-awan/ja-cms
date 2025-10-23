<?php

namespace App\Modules\Page\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_id' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'excerpt_id' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'content_id' => ['required', 'string'],
            'content_en' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:pages,id'],
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
            'title_id.required' => 'Judul halaman (Indonesia) harus diisi.',
            'title_id.max' => 'Judul halaman (Indonesia) maksimal 255 karakter.',
            'title_en.max' => 'Judul halaman (English) maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'content_id.required' => 'Konten halaman (Indonesia) harus diisi.',
            'parent_id.exists' => 'Halaman parent tidak ditemukan.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title_id' => 'Judul (Indonesia)',
            'title_en' => 'Judul (English)',
            'slug' => 'Slug',
            'excerpt_id' => 'Ringkasan (Indonesia)',
            'excerpt_en' => 'Ringkasan (English)',
            'content_id' => 'Konten (Indonesia)',
            'content_en' => 'Konten (English)',
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
