<?php

declare(strict_types=1);

use App\Administration\Resources\Tenants\RelationManagers\UsersRelationManager;
use App\Core\Models\Tenant;
use App\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render users relation manager', function (): void {
    $ownerRecord = Tenant::factory()->create();

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Tenants\Pages\EditTenant::class,
    ])->assertSuccessful();
});

it('can list tenant users', function (): void {
    $ownerRecord = Tenant::factory()->create();
    $users = User::factory()->count(3)->create();
    $ownerRecord->users()->attach($users);

    Livewire::test(UsersRelationManager::class, [
        'ownerRecord' => $ownerRecord,
        'pageClass' => \App\Administration\Resources\Tenants\Pages\EditTenant::class,
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
        'pageClass' => \App\Administration\Resources\Tenants\Pages\EditTenant::class,
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
        'pageClass' => \App\Administration\Resources\Tenants\Pages\EditTenant::class,
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
        'pageClass' => \App\Administration\Resources\Tenants\Pages\EditTenant::class,
    ])
        ->callTableBulkAction(\Filament\Actions\DetachBulkAction::class, $users)
        ->assertNotified();

    expect($ownerRecord->users()->count())->toBe(0);
});
