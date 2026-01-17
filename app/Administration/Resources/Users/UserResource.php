<?php

declare(strict_types=1);

namespace App\Administration\Resources\Users;

use App\Administration\Resources\Resource;
use App\Core\Models\User;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Configure the form's schema.
     */
    public static function form(Schema $schema): Schema
    {
        return Schemas\UserForm::configure($schema);
    }

    /**
     * Get the resource's navigation group.
     */
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return trans('Administration');
    }

    /**
     * Configure the pages for the resource.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * Get the resource's relation managers.
     *
     * @return array<class-string<\Filament\Resources\RelationManagers\RelationManager>>
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\TenantsRelationManager::class,
        ];
    }

    /**
     * Configure the table's columns.
     */
    public static function table(Table $table): Table
    {
        return Tables\UsersTable::configure($table);
    }
}
