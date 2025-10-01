<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $item) {
            $item->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        if (!$this->{$this->slugColumn()}) {
            $slug = $this->slugUnique(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value()
            );
            $this->{$this->slugColumn()} = $slug;
        }
    }

    protected function slugFrom(): string
    {
        return 'title';
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    private function slugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $i = 0;

        while ($this->isSlugExists($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;
        }

        return $slug;
    }

    private function isSlugExists(string $slug): bool
    {
        return $this->newQuery()
            ->where(self::slugColumn(), $slug)
            //->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes()
            ->exists();
    }
}
