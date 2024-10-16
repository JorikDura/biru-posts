<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth\Notification;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserNotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'array', 'min:1'],
            'id.*' => ['uuid', 'exists:notifications,id']
        ];
    }
}
