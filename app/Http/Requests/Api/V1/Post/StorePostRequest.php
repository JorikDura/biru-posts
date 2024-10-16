<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tags' => ['required', 'array', 'min:1', 'max:8'],
            'tags.*' => ['required', 'string', 'min:1', 'max:24'],
            'text' => ['required_without:images', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:102400']
        ];
    }
}
