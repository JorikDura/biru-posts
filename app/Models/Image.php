<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image as InterventionImage;
use ReflectionClass;
use ReflectionException;

class Image extends Model
{
    use HasFactory;

    private const int DEFAULT_MIN_HEIGHT = 500;
    private const int DEFAULT_MIN_WIDTH = 500;
    private const int DEFAULT_NAME_SIZE = 6;
    private const string DEFAULT_PATH = 'images/';

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

    /**
     * @param  UploadedFile  $file
     * @param  Model  $model
     * @param  ?string  $path
     * @param  ?string  $name
     * @return self
     * @throws ReflectionException
     */
    public static function create(
        UploadedFile $file,
        Model $model,
        ?string $path = null,
        ?string $name = null,
    ): self {
        $image = InterventionImage::read($file);

        $path ??= self::getPath($model::class);

        /** @var array{original: string, preview: string} $names */
        $names = self::transformName(
            name: $name,
            path: $path,
            image: $image,
            extension: $file->getClientOriginalExtension()
        );

        unset($file);

        $model = self::query()->create([
            'imageable_id' => $model->getKey(),
            'imageable_type' => $model::class,
            'original_image' => $names['original'],
            'preview_image' => $names['preview']
        ]);

        self::storeImageToDisk(
            names: $names,
            image: $image,
        );

        return $model;
    }

    /**
     * @param  array  $files
     * @param  Model  $model
     * @param  ?string  $path
     * @param  ?string  $name
     * @return void
     * @throws ReflectionException
     */
    public static function insert(
        array $files,
        Model $model,
        ?string $path = null,
        ?string $name = null,
    ): void {
        $result = [
            'data' => [],
            'images' => []
        ];

        foreach ($files as $file) {
            $image = InterventionImage::read($file);

            $path ??= self::getPath($model::class);

            /** @var array{original: string, preview: string} $names */
            $names = self::transformName(
                name: $name,
                path: $path,
                image: $image,
                extension: $file->getClientOriginalExtension()
            );

            $result['data'][] = [
                'imageable_id' => $model->getKey(),
                'imageable_type' => $model::class,
                'original_image' => $names['original'],
                'preview_image' => $names['preview']
            ];
            $result['images'][] = $image;
        }

        unset($files);

        self::query()->insert($result['data']);

        for ($i = 0, $iMax = count($result['images']); $i < $iMax; $i++) {
            self::storeImageToDisk(
                names: [
                    'original' => $result['data'][$i]['original_image'],
                    'preview' => $result['data'][$i]['preview_image']
                ],
                image: $result['images'][$i]
            );
        }
    }

    /**
     * @param  array  $names
     * @param  mixed  $image
     * @return void
     */
    private static function storeImageToDisk(
        array $names,
        mixed $image
    ): void {
        Storage::disk('public')
            ->put(
                path: $names['original'],
                contents: $image->encodeByMediaType()
            );

        if (!is_null($names['preview'])) {
            $image = $image->scale(
                height: self::DEFAULT_MIN_HEIGHT,
                width: self::DEFAULT_MIN_WIDTH
            )->encodeByMediaType();

            Storage::disk('public')
                ->put(
                    path: $names['preview'],
                    contents: $image
                );
        }
    }

    /**
     * @throws ReflectionException
     */
    private static function getPath(string $type): string
    {
        return self::DEFAULT_PATH.Str::of(
            string: (new ReflectionClass($type))->getShortName()
        )->plural()->lower()->toString().'/';
    }

    /**
     *
     * @param  ?string  $name
     * @param  string  $path
     * @param  mixed  $image
     * @param  string  $extension
     * @return array
     */
    private static function transformName(
        ?string $name,
        string $path,
        mixed $image,
        string $extension
    ): array {
        if (is_null($name)) {
            $name = Str::random(length: self::DEFAULT_NAME_SIZE);
        }

        $name .= '-'.time();

        return [
            'original' => "$path$name.$extension",
            'preview' => self::shouldScale($image)
                ? "$path$name-scaled.$extension"
                : null
        ];
    }

    /**
     * @param $image
     * @return bool
     */
    private static function shouldScale($image): bool
    {
        return ($image->height() > self::DEFAULT_MIN_HEIGHT)
            || ($image->width() > self::DEFAULT_MIN_WIDTH);
    }
}
