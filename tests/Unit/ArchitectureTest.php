<?php

declare(strict_types=1);

arch()
    ->preset()
    ->php();

arch()
    ->preset()
    ->security();

arch('annotations')
    ->expect('App')
    ->toHaveMethodsDocumented();

arch('debugging')
    ->expect(['dd', 'die', 'dump', 'ray', 'var_dump', 'var_export'])
    ->not->toBeUsed();

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('strict equality')
    ->expect('App')
    ->toUseStrictEquality();
