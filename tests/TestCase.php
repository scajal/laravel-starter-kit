<?php

declare(strict_types=1);

namespace Tests;

use App\Modules\Core\Landlord\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    /**
     * Configure the test environment and run migrations.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configureDatabases();
        $this->migrateDatabases();
    }

    /**
     * Set in-memory SQLite paths for landlord and tenant and ensure files exist.
     */
    protected function configureDatabases(): void
    {
        $landlordDatabasePath = database_path('testing-landlord.sqlite');
        $tenantDatabasePath = database_path('testing-tenant.sqlite');

        if (! file_exists($landlordDatabasePath)) {
            touch($landlordDatabasePath);
        }

        if (! file_exists($tenantDatabasePath)) {
            touch($tenantDatabasePath);
        }

        config()->set('database.connections.landlord.database', $landlordDatabasePath);
        config()->set('database.connections.tenant.database', $tenantDatabasePath);
    }

    /**
     * Run landlord and tenant migrations fresh; ensure metrics.code column exists.
     */
    protected function migrateDatabases()
    {
        Artisan::call('migrate:fresh', [
            '--path' => database_path('migrations/landlord'),
        ]);

        Artisan::call('tenants:artisan', [
            'artisanCommand' => 'migrate:fresh --database=tenant',
        ]);
    }
}
