<?php

declare(strict_types=1);

namespace Database\Seeders\Core;

use App\Core\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::factory()->create();
    }
}
