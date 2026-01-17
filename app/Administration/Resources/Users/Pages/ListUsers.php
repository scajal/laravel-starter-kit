<?php

declare(strict_types=1);

namespace App\Administration\Resources\Users\Pages;

use App\Administration\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    /**
     * Get the header actions for the list page.
     *
     * @return array<int, \Filament\Actions\ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([])
                ->actions([
                    Actions\CreateAction::make(),
                ]),
        ];
    }
}
