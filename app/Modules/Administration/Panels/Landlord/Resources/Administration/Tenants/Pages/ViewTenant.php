<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages;

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\TenantResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

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
                    Actions\Action::make(trans('Tenant panel'))
                        ->icon(Heroicon::OutlinedRectangleGroup)
                        ->url(Filament::getPanel('administration-tenant')->getUrl($this->getRecord()), true),
                ]),
        ];
    }
}
