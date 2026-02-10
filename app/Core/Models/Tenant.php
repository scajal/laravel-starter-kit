<?php

declare(strict_types=1);

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $id
 * @property string $database
 * @property string|null $domain
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\Models\User> $users
 *
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\Core\Models\TenantFactory factory($count = null, $state = [])
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Core\Models\Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Core\Models\Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Core\Models\Tenant query()
 *
 * @mixin \Eloquent
 */
class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    /** @use HasFactory<\Database\Factories\Core\Models\TenantFactory> */
    use HasFactory;

    use HasUuids;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Core\Models\User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Create the database for the tenant.
     */
    public function createDatabase(): void
    {
        if ($this->getConnection()->getDriverName() === 'sqlite') {
            file_exists("database/{$this->database}.sqlite") || touch("database/{$this->database}.sqlite");
        } else {
            $this->getConnection()->statement("CREATE DATABASE IF NOT EXISTS {$this->database}");
        }
    }

    /**
     * Executed whenever the tenant is booted.
     */
    protected static function booted(): void
    {
        static::creating(fn (self $model): mixed => $model->createDatabase());
    }
}
