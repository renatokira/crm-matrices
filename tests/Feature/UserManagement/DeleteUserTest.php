<?php

use App\Livewire\Admin;
use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to delete a user', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirm_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', [
        'id' => $forDeletion->id,
    ]);

    $forDeletion->refresh();

    expect($forDeletion)
        ->deletedBy->id->toBe($user->id);
});

it('should have a confirmation before deletion', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->call('destroy')
        ->assertHasErrors(['confirm' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('should send a notification to the user telling him that he has no long access to the application', function () {
    Notification::fake();
    $user = User::factory()->admin()->create();

    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirm_confirmation', 'DELETAR')
        ->call('destroy');

    Notification::assertSentTo($forDeletion, UserDeletedNotification::class);
});

it('should not be possible to delete the logged user', function () {
    $user = User::factory()->admin()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $user)
        ->set('confirm_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertHasErrors(['confirm' => "You can't delete yourself brow."])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $user->id]);
});
