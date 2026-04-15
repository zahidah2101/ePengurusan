<?php

use App\Livewire\Complaints\Index;
use App\Models\Complaint;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('complaints page is accessible to authenticated users', function () {
    $this->get(route('complaints.index'))->assertOk();
});

test('complaints page is not accessible to guests', function () {
    auth()->logout();
    $this->get(route('complaints.index'))->assertRedirect(route('login'));
});

test('complaints are listed', function () {
    Complaint::factory()->count(3)->create(['user_id' => $this->user->id]);

    Livewire::test(Index::class)
        ->assertSee(Complaint::first()->title);
});

test('a complaint can be created', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('title', 'Test Complaint')
        ->set('description', 'This is a test complaint description.')
        ->set('status', 'pending')
        ->call('save');

    expect(Complaint::where('title', 'Test Complaint')->exists())->toBeTrue();
});

test('a complaint can be updated', function () {
    $complaint = Complaint::factory()->create(['user_id' => $this->user->id]);

    Livewire::test(Index::class)
        ->call('openEdit', $complaint)
        ->set('title', 'Updated Title')
        ->call('save');

    expect($complaint->fresh()->title)->toBe('Updated Title');
});

test('a complaint can be deleted', function () {
    $complaint = Complaint::factory()->create(['user_id' => $this->user->id]);

    Livewire::test(Index::class)
        ->call('delete', $complaint);

    expect(Complaint::find($complaint->id))->toBeNull();
});

test('validation requires title and description', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->call('save')
        ->assertHasErrors(['title', 'description']);
});
