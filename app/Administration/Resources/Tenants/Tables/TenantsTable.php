<?php

declare(strict_types=1);

namespace App\Administration\Resources\Tenants\Tables;

use Filament\Actions;
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
                Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
