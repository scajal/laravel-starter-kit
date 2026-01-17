<?php

declare(strict_types=1);

namespace App\Administration\Resources\Tenants\Pages;

use App\Administration\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    /**
     * Get the header actions for the view page.
     *
     * @return array<int, \Filament\Actions\ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([])
                ->actions([
                    Actions\EditAction::make(),
                ]),
        ];
    }
}
