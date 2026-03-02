<?php

declare(strict_types=1);

namespace App\Modules\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * @property string $id
 * @property string $current_tenant_id
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $name
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Modules\Core\Models\Tenant|null $currentTenant
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Spatie\Multitenancy\TenantCollection<int, \App\Modules\Core\Models\Tenant> $tenants
 *
 * @method static \Database\Factories\Modules\Core\Models\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Modules\Core\Models\User query()
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\Modules\Core\Models\UserFactory> */
    use HasFactory;

    use HasUuids;
    use Notifiable;
    use UsesLandlordConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'name',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the current tenant for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Modules\Core\Models\Tenant, $this>
     */
    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    /**
     * Get the tenants that the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Modules\Core\Models\Tenant, $this>
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    /**
     * Indicate if the user belongs to the
     * provided tenant.
     */
    public function belongsToTenant(Tenant $tenant): bool
    {
        return $this->tenants()->whereKey($tenant->getKey())->exists();
    }

    /**
     * Indicate if the user can access the administration panel.
     *
     * @todo: Implement with the actual logic.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return ! App::isProduction();
    }

    /**
     * Indicate if the user can access the provided tenant.
     *
     * @param  \App\Modules\Core\Models\Tenant  $tenant
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->canAccessPanel(Filament::getPanel('administration-tenant'));
    }

    /**
     * Return the tenants that the user can access.
     *
     * @return array<\App\Modules\Core\Models\Tenant> | \Illuminate\Support\Collection<int, \App\Modules\Core\Models\Tenant>
     */
    public function getTenants(Panel $panel): array|Collection
    {
        /** @todo: Validate if the user has access to the tenant administration panel. */
        return Tenant::query()->get();
    }

    /**
     * Switch the current tenant for the user.
     */
    public function switchTenant(Tenant $tenant): self
    {
        if (! $this->belongsToTenant($tenant)) {
            abort(Response::HTTP_FORBIDDEN, trans("The user can't access this tenant."));
        }

        // Make the tenant the current one in the
        // context.
        $tenant->makeCurrent();

        // Update the user's current tenant.
        $this->forceFill([
            'current_tenant_id' => $tenant->getKey(),
        ])->save();

        // Set the current tenant relation.
        $this->setRelation('currentTenant', $tenant);

        return $this;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
