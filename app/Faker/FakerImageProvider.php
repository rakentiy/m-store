<?php

declare(strict_types=1);

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;

final class FakerImageProvider extends Base
{
    /**
     * @throws FilesystemException
     */
    public function fixturesImages(
        string $fixturesDirectory,
        string $storageDirectory,
    ): string {
        if (!Storage::exists($storageDirectory)) {
            Storage::makeDirectory($storageDirectory);
        }

        $file = $this->generator->file(
            base_path("tests/Fixtures/images/{$fixturesDirectory}"),
            //base_path($fixturesDirectory),
            Storage::path($storageDirectory),
            false
        );

        return '/storage/' . trim($storageDirectory, '/') . '/' . $file;
    }
}
