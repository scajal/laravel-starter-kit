<?php

declare(strict_types=1);

namespace App\Administration\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

abstract class Resource extends \Filament\Resources\Resource
{
    /**
     * Get the resource's active navigation icon.
     */
    public static function getActiveNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        if (($icon = static::getNavigationIcon()) === null) {
            return null;
        }

        /** @disregard P1006 */
        return Str::replace('o-', 'heroicon-s-', match (true) { // @phpstan-ignore-line
            ($icon = static::getNavigationIcon()) instanceof BackedEnum => $icon->value,
            $icon instanceof Htmlable => $icon->toHtml(),
            default => $icon,
        });
    }

    /**
     * Get the resource model's label.
     */
    public static function getModelLabel(): string
    {
        return trans(Str::lower(parent::getModelLabel()));
    }

    /**
     * Get the resource model's plural label.
     */
    public static function getPluralModelLabel(): string
    {
        return trans(parent::getPluralModelLabel());
    }

    /**
     * Get the title case for the resource model's label.
     */
    public static function getTitleCaseModelLabel(): string
    {
        return trans(Str::lower(self::getModelLabel()));
    }

    /**
     * Get the title case for the resource model's plural label.
     */
    public static function getTitleCasePluralModelLabel(): string
    {
        return trans(Str::ucfirst(Str::lower(parent::getTitleCasePluralModelLabel())));
    }
}
