<?php

namespace App\Modules\Tag\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    // ...validation rules

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
