<?php

declare(strict_types=1);

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Pages\EditUser;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\RelationManagers\TenantsRelationManager;
use App\Modules\Core\Models\Tenant;
use App\Modules\Core\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel('administration-landlord');

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render tenants relation manager', function (): void {
    $ownerRecord = User::factory()->create();

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditUser::class,
    ])->assertSuccessful();
});

it('can list user tenants', function (): void {
    $ownerRecord = User::factory()->create();
    $tenants = Tenant::factory()->count(3)->create();
    $ownerRecord->tenants()->attach($tenants);

    Livewire::test(TenantsRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditUser::class,
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
        'pageClass' => EditUser::class,
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
        'pageClass' => EditUser::class,
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
        'pageClass' => EditUser::class,
    ])
        ->callTableBulkAction(\Filament\Actions\DetachBulkAction::class, $tenants)
        ->assertNotified();

    expect($ownerRecord->tenants()->count())->toBe(0);
});
