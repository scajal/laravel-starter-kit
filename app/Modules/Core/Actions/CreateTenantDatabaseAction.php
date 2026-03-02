<?php

declare(strict_types=1);

namespace App\Modules\Core\Actions;

use App\Modules\Core\Models\Tenant;
use Illuminate\Support\Str;

class CreateTenantDatabaseAction
{
    /**
     * Create an instance of the class.
     */
    public function __construct(
        protected Tenant $tenant,
    ) {}

    /**
     * Create an instance of the class and set the tenant
     * for which the database will be created.
     */
    public static function make(Tenant $tenant): self
    {
        return new self($tenant);
    }

    /**
     * Create the database for the tenant.
     */
    public function create(): void
    {
        if ($this->tenant->getConnection()->getDriverName() === 'sqlite') {
            $this->createSqliteDatabase();
        } else {
            $this->createDatabase();
        }
    }

    /**
     * Create the SQLite database for the tenant.
     */
    protected function createSqliteDatabase(): void
    {
        // Ensure the database file has the correct extension.
        if (! Str::endsWith($this->tenant->database, '.sqlite')) {
            $this->tenant->database = database_path($this->tenant->database.'.sqlite');
        }

        // Ensure the database file is created.
        if (! file_exists($this->tenant->database)) {
            // Create the database file.
            touch($this->tenant->database);
        }
    }

    /**
     * Create the database for the tenant.
     */
    protected function createDatabase(): void
    {
        // Create the database.
        $this->tenant->getConnection()->statement(sprintf('CREATE DATABASE IF NOT EXISTS `%s`;', $this->tenant->database));
    }
}
