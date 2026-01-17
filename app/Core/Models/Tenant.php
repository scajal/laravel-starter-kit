<?php

declare(strict_types=1);

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    /** @use HasFactory<\Database\Factories\Core\Models\TenantFactory> */
    use HasFactory;

    use HasUuids;
}
