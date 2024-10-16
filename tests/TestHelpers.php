<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Random\RandomException;

class TestHelpers
{
    private const int IMAGE_WIDTH = 600;
    private const int IMAGE_HEIGHT = 600;

    /**
     * @throws RandomException
     */
    public static function randomUploadedFiles(int $min = 2, int $max = 8): array
    {
        $files = [];

        for ($i = 1; $i < random_int($min, $max); $i++) {
            $files[] = static::uploadFile("test_$i.jpg");
        }

        return $files;
    }

    public static function uploadFile(
        string $name,
        int $width = self::IMAGE_WIDTH,
        int $height = self::IMAGE_HEIGHT
    ): File {
        return UploadedFile::fake()->image(
            name: $name,
            width: $width,
            height: $height
        );
    }

    public static function storeFakeFiles(
        string $path,
        string $name,
        string $disk = 'public',
        int $width = self::IMAGE_WIDTH,
        int $height = self::IMAGE_HEIGHT
    ) {
        return Storage::disk($disk)
            ->put(
                path: $path,
                contents: TestHelpers::uploadFile(
                    name: $name,
                    width: $width,
                    height: $height
                )
            );
    }
}
