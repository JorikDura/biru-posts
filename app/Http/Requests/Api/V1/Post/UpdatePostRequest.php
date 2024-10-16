<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Post;

use App\Rules\CounterRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tags' => [
                'nullable',
                'array',
                'max:8',
                new CounterRule(
                    model: $this->route('post'),
                    relation: 'tags'
                )
            ],
            'tags.*' => ['nullable', 'string', 'min:1', 'max:24'],
            'text' => ['nullable', 'string', 'max:255'],
            'images' => [
                'nullable',
                'array',
                'max:10',
                new CounterRule(
                    model: $this->route('post'),
                    relation: 'images',
                    max: 10
                )
            ],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:102400'],
        ];
    }
}
