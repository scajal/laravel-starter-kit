<?php

declare(strict_types=1);

use App\Administration\Resources\Tenants\Pages\CreateTenant;
use App\Administration\Resources\Tenants\Pages\EditTenant;
use App\Administration\Resources\Tenants\Pages\ListTenants;
use App\Administration\Resources\Tenants\Pages\ViewTenant;
use App\Core\Models\Tenant;
use App\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list tenants', function (): void {
    Tenant::factory()->count(3)->create();

    Livewire::test(ListTenants::class)
        ->assertSuccessful();
});

it('can search tenants by name', function (): void {
    Tenant::factory()->create(['name' => 'Acme Corporation']);
    Tenant::factory()->create(['name' => 'Global Industries']);

    Livewire::test(ListTenants::class)
        ->assertSuccessful()
        ->searchTable('Acme')
        ->assertSuccessful();
});

it('can create a tenant', function (): void {
    Livewire::test(CreateTenant::class)
        ->fillForm([
            'name' => 'Test Tenant',
        ])
        ->call('create')
        ->assertNotified();

    $this->assertDatabaseHas('tenants', [
        'name' => 'Test Tenant',
    ]);
});

it('validates required fields when creating a tenant', function (): void {
    Livewire::test(CreateTenant::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ])
        ->assertNotNotified();
});

it('can view a tenant', function (): void {
    $tenant = Tenant::factory()->create();

    Livewire::test(ViewTenant::class, ['record' => $tenant->id])
        ->assertSuccessful();
});

it('can edit a tenant', function (): void {
    $tenant = Tenant::factory()->create([
        'name' => 'Original Name',
    ]);

    Livewire::test(EditTenant::class, ['record' => $tenant->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertNotified();

    expect($tenant->fresh())
        ->name->toBe('Updated Name');
});

it('validates required fields when editing a tenant', function (): void {
    $tenant = Tenant::factory()->create();

    Livewire::test(EditTenant::class, ['record' => $tenant->id])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
        ])
        ->assertNotNotified();
});

it('can delete a tenant', function (): void {
    $tenant = Tenant::factory()->create();

    Livewire::test(EditTenant::class, ['record' => $tenant->id])
        ->callAction(\Filament\Actions\DeleteAction::class)
        ->assertNotified();

    $this->assertDatabaseMissing('tenants', [
        'id' => $tenant->id,
    ]);
});
