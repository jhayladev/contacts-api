<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

it('creates contacts associated with a user', function () {
    $user = User::factory()->create();

    $contact = Contact::factory()->create([
        'user_id' => $user->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'deleted_by' => null,
    ]);

    expect($contact->user->is($user))->toBeTrue();
});

it('creates multiple contacts for a user', function () {
    $user = User::factory()->create();

    $contacts = Contact::factory(3)->create([
        'user_id' => $user->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'deleted_by' => null,
    ]);

    expect($contacts->count())->toBe(3);
    foreach ($contacts as $contact) {
        expect($contact->user->is($user))->toBeTrue();
    }
});

it('ensures created_by, updated_by, and deleted_by fields are set correctly', function () {
    $user = User::factory()->create();

    $contact = Contact::factory()->create([
        'user_id' => $user->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'deleted_by' => null,
    ]);

    expect($contact->created_by)->toBe($user->id);
    expect($contact->updated_by)->toBe($user->id);
    expect($contact->deleted_by)->toBeNull();
});

it('users with the same email address are not accepted', function () {
    User::factory()->create([
        'email' => 'john.doe@example.com',
    ]);

    User::factory()->create([
        'email' => 'john.doe@example.com',
    ]);
})->throws(UniqueConstraintViolationException::class);
