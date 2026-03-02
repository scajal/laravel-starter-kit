<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Schemas;

use App\Modules\Core\Models\User;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;

class UserForm
{
    /**
     * Configure the form's schema.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns($schema->getOperation() === Operation::Create->value ? 1 : 3)
            ->components([
                Schemas\Components\Tabs::make()
                    ->columnSpan($schema->getOperation() === Operation::Create->value ? null : [Width::Medium->value => 2])
                    ->schema(self::schema()),
                Schemas\Components\Section::make()
                    ->disabled()
                    ->visible($schema->getOperation() !== Operation::Create->value)
                    ->schema([Forms\Components\DateTimePicker::make('created_at'), Forms\Components\DateTimePicker::make('updated_at')]),
            ]);
    }

    /**
     * Get the schema of the form.
     *
     * @return array<int, \Filament\Schemas\Components\Tabs\Tab>
     */
    protected static function schema(): array
    {
        return [
            Schemas\Components\Tabs\Tab::make('General')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->string()
                        ->maxLength(255),
                    Forms\Components\Select::make(new User()->currentTenant()->getForeignKeyName())
                        ->columnSpanFull()
                        ->relationship('currentTenant', 'name', fn (Builder $query, User $record): Builder => $query->whereHas('users', fn (Builder $query): Builder => $query->whereKey($record->getKey())))
                        ->searchable()
                        ->preload(),
                ]),
        ];
    }
}
