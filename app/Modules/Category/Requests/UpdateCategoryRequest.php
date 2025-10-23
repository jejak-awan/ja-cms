<?php

namespace App\Modules\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category');
        
        return [
            'name_id' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('categories', 'slug')->ignore($categoryId)
            ],
            'description_id' => ['nullable', 'string', 'max:1000'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                Rule::notIn([$categoryId]) // Prevent self-referencing
            ],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name_id.required' => 'Category name (Indonesian) is required.',
            'name_id.max' => 'Category name (Indonesian) cannot exceed 255 characters.',
            'name_en.max' => 'Category name (English) cannot exceed 255 characters.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'This slug is already in use.',
            'parent_id.exists' => 'Selected parent category does not exist.',
            'parent_id.not_in' => 'A category cannot be its own parent.',
            'color.regex' => 'Color must be a valid hex code (e.g., #FF5733).',
            'order.min' => 'Order must be a positive number.',
            'meta_title.max' => 'Meta title cannot exceed 255 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
        ];
    }

    /**
     * Get custom attributes.
     */
    public function attributes(): array
    {
        return [
            'parent_id' => 'parent category',
            'is_active' => 'active status',
            'meta_title' => 'SEO title',
            'meta_description' => 'SEO description',
            'meta_keywords' => 'SEO keywords',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert is_active checkbox to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        // If slug is empty, it will be auto-generated
        if (empty($this->slug)) {
            $this->merge(['slug' => null]);
        }

        // If parent_id is empty string, set to null
        if ($this->parent_id === '') {
            $this->merge(['parent_id' => null]);
        }
    }
}
