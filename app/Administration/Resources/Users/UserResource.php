<?php

declare(strict_types=1);

namespace App\Administration\Resources\Users;

use App\Administration\Resources\Resource;
use App\Administration\Resources\Users\Pages\CreateUser;
use App\Administration\Resources\Users\Pages\EditUser;
use App\Administration\Resources\Users\Pages\ListUsers;
use App\Administration\Resources\Users\Pages\ViewUser;
use App\Administration\Resources\Users\Schemas\UserForm;
use App\Administration\Resources\Users\Tables\UsersTable;
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

    /**
     * Configure the form's schema.
     */
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * Configure the table's columns.
     */
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }
}
