<?php

use App\Models\Contact;
use App\Models\User;

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
