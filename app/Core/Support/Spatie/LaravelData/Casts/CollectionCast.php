<?php

declare(strict_types=1);

namespace App\Core\Support\Spatie\LaravelData\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class CollectionCast implements Cast
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed // @phpstan-ignore-line
    {
        // Cast the items of the collection to
        // the corresponding enum if corresponds.
        return collect($value)->map(function (mixed $item) use ($property): mixed { // @phpstan-ignore-line
            if (is_string($type = $property->type->iterableItemType) && enum_exists($type) && filled($item)) {
                return $item instanceof $type ? $item : $type::tryFrom($item); // @phpstan-ignore-line
            }

            return $item;
        });
    }
}
