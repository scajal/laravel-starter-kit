<?php

declare(strict_types=1);

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages\CreateTenant;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages\EditTenant;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages\ListTenants;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Tenants\Pages\ViewTenant;
use App\Modules\Core\Models\Tenant;
use App\Modules\Core\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    /** @var \Tests\TestCase $this */
    Filament::setCurrentPanel('administration-landlord');

    /** @disregard P1014 */
    $this->user = User::factory()->create();

    /** @disregard P1014 */
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

    /** @var \Tests\TestCase $this */
    $this->assertDatabaseHas('tenants', [
        'name' => 'Test Tenant',
    ], 'landlord');
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

it('validates unique name when creating a tenant', function (): void {
    Tenant::factory()->create(['name' => 'Existing Tenant']);

    Livewire::test(CreateTenant::class)
        ->fillForm([
            'name' => 'Existing Tenant',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
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

it('validates unique name when editing a tenant', function (): void {
    $tenant1 = Tenant::factory()->create(['name' => 'Tenant One']);
    $tenant2 = Tenant::factory()->create(['name' => 'Tenant Two']);

    Livewire::test(EditTenant::class, ['record' => $tenant1->id])
        ->fillForm([
            'name' => 'Tenant Two',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'unique',
        ])
        ->assertNotNotified();
});

it('can delete a tenant', function (): void {
    $tenant = Tenant::factory()->create();

    Livewire::test(EditTenant::class, ['record' => $tenant->id])
        ->callAction(\Filament\Actions\DeleteAction::class)
        ->assertNotified();

    /** @var \Tests\TestCase $this */
    $this->assertDatabaseMissing('tenants', [
        'id' => $tenant->id,
    ], 'landlord');
});

it('can bulk delete tenants', function (): void {
    $tenants = Tenant::factory()->count(3)->create();

    Livewire::test(ListTenants::class)
        ->callTableBulkAction(\Filament\Actions\DeleteBulkAction::class, $tenants)
        ->assertNotified();

    /** @var \Tests\TestCase $this */
    foreach ($tenants as $tenant) {
        $this->assertDatabaseMissing('tenants', [
            'id' => $tenant->id,
        ], 'landlord');
    }
});
