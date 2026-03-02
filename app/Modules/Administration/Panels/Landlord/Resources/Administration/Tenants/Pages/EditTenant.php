<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages;

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\TenantResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

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
                    Actions\Action::make(trans('Tenant panel'))
                        ->icon(Heroicon::OutlinedRectangleGroup)
                        ->url(Filament::getPanel('administration-tenant')->getUrl($this->getRecord()), true),
                ]),
        ];
    }
}
