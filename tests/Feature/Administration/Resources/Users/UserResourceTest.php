<?php

declare(strict_types=1);

use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Pages\CreateUser;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Pages\EditUser;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Pages\ListUsers;
use App\Modules\Administration\Panels\Landlord\Resources\Administration\Users\Pages\ViewUser;
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

it('can list users', function (): void {
    $users = User::factory()->count(3)->create();

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});

it('can search users by name', function (): void {
    $user1 = User::factory()->create(['name' => 'John Doe']);
    $user2 = User::factory()->create(['name' => 'Jane Smith']);

    Livewire::test(ListUsers::class)
        ->assertSuccessful()
        ->searchTable('John')
        ->assertSuccessful();
});

it('can search users by email', function (): void {
    $user1 = User::factory()->create(['email' => 'john@example.com']);
    $user2 = User::factory()->create(['email' => 'jane@example.com']);

    Livewire::test(ListUsers::class)
        ->assertSuccessful()
        ->searchTable('john@example.com')
        ->assertSuccessful();
});

it('can create a user', function (): void {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->call('create')
        ->assertNotified();

    /** @var \Tests\TestCase $this */
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ], 'landlord');
});

it('validates required fields when creating a user', function (): void {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
        ])
        ->assertNotNotified();
});

it('validates email format when creating a user', function (): void {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'email',
        ])
        ->assertNotNotified();
});

it('validates unique email when creating a user', function (): void {
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'existing@example.com',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'unique',
        ])
        ->assertNotNotified();
});

it('can view a user', function (): void {
    $user = User::factory()->create();

    Livewire::test(ViewUser::class, ['record' => $user->id])
        ->assertSuccessful();
});

it('can edit a user', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertNotified();

    expect($user->fresh())
        ->name->toBe('Updated Name')
        ->email->toBe('updated@example.com');
});

it('validates required fields when editing a user', function (): void {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
        ])
        ->assertNotNotified();
});

it('validates email format when editing a user', function (): void {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => $user->name,
            'email' => 'invalid-email',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'email' => 'email',
        ])
        ->assertNotNotified();
});

it('validates unique email when editing a user', function (): void {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);

    Livewire::test(EditUser::class, ['record' => $user1->id])
        ->fillForm([
            'name' => $user1->name,
            'email' => 'user2@example.com',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'email' => 'unique',
        ])
        ->assertNotNotified();
});

it('can delete a user', function (): void {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->id])
        ->callAction(\Filament\Actions\DeleteAction::class)
        ->assertNotified();

    /** @var \Tests\TestCase $this */
    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ], 'landlord');
});

it('can bulk delete users', function (): void {
    $users = User::factory()->count(3)->create();

    Livewire::test(ListUsers::class)
        ->callTableBulkAction(\Filament\Actions\DeleteBulkAction::class, $users)
        ->assertNotified();

    /** @var \Tests\TestCase $this */
    foreach ($users as $user) {
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ], 'landlord');
    }
});
