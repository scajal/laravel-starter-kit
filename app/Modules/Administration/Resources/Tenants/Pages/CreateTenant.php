<?php

declare(strict_types=1);

namespace App\Modules\Administration\Resources\Tenants\Pages;

use App\Modules\Administration\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;
}
