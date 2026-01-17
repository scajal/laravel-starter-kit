<?php

declare(strict_types=1);

namespace App\Administration\Resources\Users\RelationManagers;

use App\Administration\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TenantsRelationManager extends RelationManager
{
    protected static string $relationship = 'tenants';

    protected static ?string $relatedResource = TenantResource::class;

    /**
     * Configure the table's columns.
     */
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Actions\AttachAction::make()
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->recordActions([
                Actions\DetachAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
