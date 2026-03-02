<?php

declare(strict_types=1);

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages\EditTenant;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\RelationManagers\UsersRelationManager;
use App\Modules\Core\Models\Tenant;
use App\Modules\Core\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel('administration-landlord');

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render users relation manager', function (): void {
    $ownerRecord = Tenant::factory()->create();

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditTenant::class,
    ])->assertSuccessful();
});

it('can list tenant users', function (): void {
    $ownerRecord = Tenant::factory()->create();
    $users = User::factory()->count(3)->create();
    $ownerRecord->users()->attach($users);

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditTenant::class,
    ])
        ->assertSuccessful()
        ->loadTable()
        ->assertCanSeeTableRecords($users);
});

it('can attach users to tenant', function (): void {
    $ownerRecord = Tenant::factory()->create();
    $users = User::factory()->count(2)->create();

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditTenant::class,
    ])
        ->callTableAction(\Filament\Actions\AttachAction::class, data: [
            'recordId' => $users->pluck('id')->toArray(),
        ])
        ->assertNotified();

    expect($ownerRecord->users()->count())->toBe(2);
});

it('can detach a user from tenant', function (): void {
    $ownerRecord = Tenant::factory()->create();
    $user = User::factory()->create();
    $ownerRecord->users()->attach($user);

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditTenant::class,
    ])
        ->callTableAction(\Filament\Actions\DetachAction::class, record: $user)
        ->assertNotified();

    expect($ownerRecord->users()->where('users.id', $user->id)->exists())->toBeFalse();
});

it('can bulk detach users from tenant', function (): void {
    $ownerRecord = Tenant::factory()->create();
    $users = User::factory()->count(3)->create();
    $ownerRecord->users()->attach($users);

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => EditTenant::class,
    ])
        ->callTableBulkAction(\Filament\Actions\DetachBulkAction::class, $users)
        ->assertNotified();

    expect($ownerRecord->users()->count())->toBe(0);
});
