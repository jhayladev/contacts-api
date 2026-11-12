<?php

use App\Models\Contact;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('crea un contacto válido', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/contacts', [
        'name' => 'Addu Nuñez',
        'email' => 'addu.nunez@example.com',
    ]);
    $response->assertCreated()
        ->assertJsonPath('data.name', 'Addu Nuñez')
        ->assertJsonPath('data.email', 'addu.nunez@example.com');

    $this->assertDatabaseHas('contacts', [
        'user_id' => $user->id,
        'name' => 'Addu Nuñez',
        'email' => 'addu.nunez@example.com',
    ]);
});

test('devuelve 403 para un contacto ajeno', function () {
    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    $contact = Contact::factory()->for($owner)->create([
        'name' => 'Contacto privado',
        'email' => 'contacto.privado@example.com',
    ]);

    Sanctum::actingAs($visitor);

    $this->getJson('/api/v1/contacts/'.$contact->id)
        ->assertForbidden();

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'user_id' => $owner->id,
        'name' => 'Contacto privado',
        'email' => 'contacto.privado@example.com',
    ]);
});
