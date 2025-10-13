<?php

namespace App\Modules\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    // ...validation rules

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
