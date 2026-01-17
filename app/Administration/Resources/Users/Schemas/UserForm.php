<?php

declare(strict_types=1);

namespace App\Administration\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Filament\Support\Enums\Width;

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
                ]),
        ];
    }
}
