<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToUser;
use App\Traits\HasComments;
use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;
    use HasComments;
    use HasImages;
    use BelongsToUser;

    protected $fillable = [
        'text',
        'user_id'
    ];

    public function delete(): ?bool
    {
        $this->deleteImages();

        $this->deleteComments();

        return parent::delete();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'like_post');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function loadFull(): self
    {
        return $this->load([
            'tags',
            'images',
            'user:id,username' => ['image']
        ]);
    }
}
