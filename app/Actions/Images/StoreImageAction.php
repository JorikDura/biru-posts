<?php

declare(strict_types=1);

namespace App\Actions\Images;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image as InterventionImage;
use ReflectionClass;
use ReflectionException;

final readonly class StoreImageAction
{
    private const int DEFAULT_MIN_HEIGHT = 500;
    private const int DEFAULT_MIN_WIDTH = 500;
    private const int DEFAULT_NAME_SIZE = 6;
    private const string DEFAULT_FILE_EXTENSION = '.jpg';
    private const string DEFAULT_PATH = 'images/';

    /**
     * @param  UploadedFile  $file
     * @param  Model  $model
     * @param  ?string  $path
     * @param  ?string  $name
     * @return Image
     * @throws ReflectionException
     */
    public function store(
        UploadedFile $file,
        Model $model,
        ?string $path = null,
        ?string $name = null,
    ): Image {
        $image = InterventionImage::read($file);

        $path ??= $this->getPath($model::class);

        /** @var array{original: string, preview: string} $names */
        $names = $this->transformName(
            name: $name,
            path: $path,
            shouldScale: $this->shouldScale($image)
        );

        $dbImage = Image::create([
            'imageable_id' => $model->getKey(),
            'imageable_type' => $model::class,
            'original_image' => $names['original'],
            'preview_image' => $names['preview']
        ]);

        $this->storeImageToDisk(
            names: $names,
            image: $image
        );

        return $dbImage;
    }

    /**
     * @param  array  $files
     * @param  Model  $model
     * @param  ?string  $path
     * @param  ?string  $name
     * @return void
     * @throws ReflectionException
     */
    public function storeMany(
        array $files,
        Model $model,
        ?string $path = null,
        ?string $name = null,
    ): void {
        $dbImagesData = [];
        $convertedImages = [];

        foreach ($files as $file) {
            $image = InterventionImage::read($file);

            $path ??= $this->getPath($model::class);

            /** @var array{original: string, preview: string} $names */
            $names = $this->transformName(
                name: $name,
                path: $path,
                shouldScale: $this->shouldScale($image)
            );

            $dbImagesData[] = [
                'imageable_id' => $model->getKey(),
                'imageable_type' => $model::class,
                'original_image' => $names['original'],
                'preview_image' => $names['preview']
            ];
            $convertedImages[] = $image;
        }

        unset($files);

        Image::insert($dbImagesData);

        for ($i = 0; $i < count($dbImagesData); $i++) {
            $this->storeImageToDisk(
                names: [
                    'original' => $dbImagesData[$i]['original_image'],
                    'preview' => $dbImagesData[$i]['preview_image']
                ],
                image: $convertedImages[$i]
            );
        }
    }

    /**
     *
     * @param  ?string  $name
     * @param  string  $path
     * @param  bool  $shouldScale
     * @return array
     */
    private function transformName(
        ?string $name,
        string $path,
        bool $shouldScale
    ): array {
        if (is_null($name)) {
            $name = Str::random(length: self::DEFAULT_NAME_SIZE);
        }

        $name .= '-'.time();

        return [
            'original' => "$path$name".self::DEFAULT_FILE_EXTENSION,
            'preview' => $shouldScale
                ? "$path$name-scaled".self::DEFAULT_FILE_EXTENSION
                : null
        ];
    }

    /**
     * @param  array  $names
     * @param  mixed  $image
     * @return void
     */
    private function storeImageToDisk(
        array $names,
        mixed $image,
    ): void {
        /** @var UploadedFile $imageJpg */
        $imageJpg = $image->toJpeg();

        Storage::disk('public')
            ->put(
                path: $names['original'],
                contents: $imageJpg
            );

        if (!is_null($names['preview'])) {
            /** @var UploadedFile $imageJpg */
            $imageJpg = $image->scale(
                height: self::DEFAULT_MIN_HEIGHT,
                width: self::DEFAULT_MIN_WIDTH
            )->toJpeg();

            Storage::disk('public')
                ->put(
                    path: $names['preview'],
                    contents: $imageJpg
                );
        }
    }

    /**
     * @throws ReflectionException
     */
    private function getPath(string $type): string
    {
        return self::DEFAULT_PATH.Str::of(
            string: (new ReflectionClass($type))->getShortName()
        )->plural()->lower()->toString().'/';
    }

    private function shouldScale($image): bool
    {
        return ($image->height() > self::DEFAULT_MIN_HEIGHT)
            || ($image->width() > self::DEFAULT_MIN_WIDTH);
    }
}
