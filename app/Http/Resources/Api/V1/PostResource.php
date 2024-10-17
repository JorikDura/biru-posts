<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            $this->mergeWhen(!$this->relationLoaded('user'), ['user_id' => $this->user_id]),
            'text' => $this->text,
            'likes_count' => $this->whenHas('likes_count'),
            'is_liked' => $this->whenHas('is_liked'),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
