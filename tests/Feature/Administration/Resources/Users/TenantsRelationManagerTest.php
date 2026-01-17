<?php

declare(strict_types=1);

use App\Administration\Resources\Users\RelationManagers\TenantsRelationManager;
use App\Core\Models\Tenant;
use App\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render tenants relation manager', function (): void {
    $ownerRecord = User::factory()->create();

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Users\Pages\EditUser::class,
    ])->assertSuccessful();
});

it('can list user tenants', function (): void {
    $ownerRecord = User::factory()->create();
    $tenants = Tenant::factory()->count(3)->create();
    $ownerRecord->tenants()->attach($tenants);

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Users\Pages\EditUser::class,
    ])
        ->assertSuccessful()
        ->loadTable()
        ->assertCanSeeTableRecords($tenants);
});

it('can attach tenants to user', function (): void {
    $ownerRecord = User::factory()->create();
    $tenants = Tenant::factory()->count(2)->create();

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Users\Pages\EditUser::class,
    ])
        ->callTableAction(\Filament\Actions\AttachAction::class, data: [
            'recordId' => $tenants->pluck('id')->toArray(),
        ])
        ->assertNotified();

    expect($ownerRecord->tenants()->count())->toBe(2);
});

it('can detach a tenant from user', function (): void {
    $ownerRecord = User::factory()->create();
    $tenant = Tenant::factory()->create();
    $ownerRecord->tenants()->attach($tenant);

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Users\Pages\EditUser::class,
    ])
        ->callTableAction(\Filament\Actions\DetachAction::class, record: $tenant)
        ->assertNotified();

    expect($ownerRecord->tenants()->where('tenants.id', $tenant->id)->exists())->toBeFalse();
});

it('can bulk detach tenants from user', function (): void {
    $ownerRecord = User::factory()->create();
    $tenants = Tenant::factory()->count(3)->create();
    $ownerRecord->tenants()->attach($tenants);

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Users\Pages\EditUser::class,
    ])
        ->callTableBulkAction(\Filament\Actions\DetachBulkAction::class, $tenants)
        ->assertNotified();

    expect($ownerRecord->tenants()->count())->toBe(0);
});
