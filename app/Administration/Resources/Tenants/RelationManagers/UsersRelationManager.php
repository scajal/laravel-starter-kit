<?php

declare(strict_types=1);

namespace App\Administration\Resources\Tenants\RelationManagers;

use App\Administration\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $relatedResource = UserResource::class;

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
