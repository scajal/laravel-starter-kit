<?php

declare(strict_types=1);

namespace App\Administration\Resources\Tenants\Pages;

use App\Administration\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    /**
     * Get the header actions for the edit page.
     *
     * @return array<int, \Filament\Actions\ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([])
                ->actions([
                    Actions\DeleteAction::make(),
                ]),
        ];
    }
}
