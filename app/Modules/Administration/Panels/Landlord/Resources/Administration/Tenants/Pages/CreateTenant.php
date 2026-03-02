<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages;

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;

    /**
     * Mutate the form data before creating the record.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var string $name */
        $name = $data['name'];

        $data['database'] = Str::snake($name);

        return $data;
    }
}
