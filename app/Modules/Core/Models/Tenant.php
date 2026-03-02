<?php

declare(strict_types=1);

namespace App\Modules\Core\Models;

use App\Modules\Core\Actions\CreateTenantDatabaseAction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * @property string $id
 * @property string $database
 * @property string|null $domain
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Core\Models\User> $users
 *
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\Modules\Core\Models\TenantFactory factory($count = null, $state = [])
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\Tenant query()
 *
 * @mixin \Eloquent
 */
class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    /** @use HasFactory<\Database\Factories\Modules\Core\Models\TenantFactory> */
    use HasFactory;

    use HasUuids;
    use UsesLandlordConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'database',
        'name',
    ];

    /**
     * Get the users that belong to the tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Modules\Core\Models\User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Executed whenever the tenant is booted.
     */
    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant): void {
            CreateTenantDatabaseAction::make($tenant)->create();
        });

        static::created(function (Tenant $tenant): void {
            Artisan::call('tenants:artisan', [
                'artisanCommand' => 'migrate:fresh --force --database=tenant',
                '--tenant' => $tenant->getKey(),
            ]);
        });
    }
}
