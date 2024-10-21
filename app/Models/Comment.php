<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use App\Traits\Relations\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;
    use HasImages;
    use BelongsToUser;

    protected $fillable = [
        'user_id',
        'comment_id',
        'commentable_id',
        'commentable_type',
        'text'
    ];

    public function comments(): MorphTo
    {
        return $this->morphTo();
    }

    public function delete(): ?bool
    {
        $this->deleteImages();

        return parent::delete();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'like_comment');
    }

    public function isCommentableUser(int $userId): bool
    {
        return $this->commentable_type === User::class && $this->commentable_id === $userId;
    }
}
