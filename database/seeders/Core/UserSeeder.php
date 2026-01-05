<?php

declare(strict_types=1);

namespace Database\Seeders\Core;

use App\Core\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }
}
