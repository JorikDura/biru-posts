<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:48'],
        ];
    }
}
