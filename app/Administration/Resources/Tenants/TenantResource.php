<?php

declare(strict_types=1);

namespace App\Administration\Resources\Tenants;

use App\Administration\Resources\Resource;
use App\Core\Models\Tenant;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Configure the form's schema.
     */
    public static function form(Schema $schema): Schema
    {
        return Schemas\TenantForm::configure($schema);
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
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
            RelationManagers\UsersRelationManager::class,
        ];
    }

    /**
     * Configure the table's columns.
     */
    public static function table(Table $table): Table
    {
        return Tables\TenantsTable::configure($table);
    }
}
