<?php

declare(strict_types=1);

namespace Tests;

use App\Modules\Core\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->configureDatabases();
        $this->migrateDatabases();
    }

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

    protected function migrateDatabases()
    {
        Artisan::call('migrate:fresh', [
            '--database' => 'landlord',
            '--path' => database_path('migrations/landlord'),
            '--realpath' => true,
        ]);

        $tenantMigrationsPath = database_path('migrations/tenant');

        if (Tenant::count() > 0 && File::isDirectory($tenantMigrationsPath) && count(File::files($tenantMigrationsPath)) > 0) {
            Artisan::call('tenants:artisan', [
                'artisanCommand' => 'migrate:fresh --database=tenant --path='.$tenantMigrationsPath,
            ]);
        }
    }
}
