<?php

declare(strict_types=1);

namespace Database\Seeders\Core;

use App\Modules\Core\Models\Tenant;
use App\Modules\Core\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Tenant::checkCurrent()) {
            return;
        }

        User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }
}
