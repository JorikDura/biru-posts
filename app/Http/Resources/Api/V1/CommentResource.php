<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Comment
 */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            $this->mergeWhen(!$this->relationLoaded('user'), ['user_id' => $this->user_id,]),
            'comment_id' => $this->comment_id,
            'text' => $this->text,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
