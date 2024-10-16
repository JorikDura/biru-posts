<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'original_image',
        'preview_image'
    ];

    public function image(): MorphTo
    {
        return $this->morphTo();
    }

    public function delete(): ?bool
    {
        $this->deleteImagesInStorage();

        return parent::delete();
    }

    public function deleteImagesInStorage(): void
    {
        Storage::disk('public')
            ->delete($this->original_image);

        if (!is_null($this->preview_image)) {
            Storage::disk('public')
                ->delete($this->preview_image);
        }
    }
}
