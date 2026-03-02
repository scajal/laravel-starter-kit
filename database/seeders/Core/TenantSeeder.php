<?php

declare(strict_types=1);

namespace Database\Seeders\Core;

use App\Modules\Core\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Tenant::checkCurrent()) {
            return;
        }

        Tenant::factory()->create();
    }
}
