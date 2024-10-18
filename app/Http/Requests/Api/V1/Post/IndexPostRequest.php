<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class IndexPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => ['nullable', 'array'],
            'filter.user_creator_id' => ['nullable', 'int', 'exists:users,id'],
            'filter.user_liked_id' => ['nullable', 'prohibited_if:filter.is_liked,true', 'int', 'exists:users,id'],
            'filter.is_liked' => ['nullable', 'boolean']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->input('filter.is_liked') && !auth('sanctum')->check()) {
                    $validator->errors()->add(
                        key: 'filter.is_liked',
                        message: __('validation.custom.no-user-id', ['attribute' => 'filter.is_liked'])
                    );
                }
            }
        ];
    }
}
