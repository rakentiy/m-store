<?php

declare(strict_types=1);

namespace Support\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

final class FakerImageProvider extends Base
{
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
