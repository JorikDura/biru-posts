<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Image;

use Illuminate\Foundation\Http\FormRequest;

class DeleteImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'array'],
            'id.*' => ['required', 'int', 'exists:images,id']
        ];
    }
}
