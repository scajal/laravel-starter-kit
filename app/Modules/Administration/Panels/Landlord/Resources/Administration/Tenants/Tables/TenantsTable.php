<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Tables;

use App\Modules\Core\Models\Tenant;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class TenantsTable
{
    /**
     * Configure the table's columns.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(isIndividual: true),
            ])
            ->recordActions([
                Actions\ActionGroup::make([])
                    ->label('')
                    ->color(Color::Zinc)
                    ->outlined()
                    ->actions([
                        Actions\ViewAction::make(),
                        Actions\Action::make(trans('Tenant panel'))
                            ->icon(Heroicon::OutlinedRectangleGroup)
                            ->url(fn (Tenant $record): ?string => Filament::getPanel('administration-tenant')->getUrl($record), true),
                    ]),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
