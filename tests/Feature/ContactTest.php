<?php

use App\Models\Contact;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function autenticado(): User
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    return $user;
}

it('pide token para listar contactos', function () {
    $this->getJson('/api/v1/contacts')->assertUnauthorized();
});

it('lista solo los contactos del usuario autenticado', function () {
    $user = autenticado();
    Contact::factory(2)->create(['user_id' => $user->id]);
    Contact::factory(3)->create();

    $this->getJson('/api/v1/contacts')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('crea un contacto y registra la auditoría', function () {
    $user = autenticado();

    $this->postJson('/api/v1/contacts', [
        'name' => 'Ana Pérez',
        'email' => 'ana@example.com',
        'address' => 'Calle 123, Ciudad',
        'phone_number' => '1234567890',
        'url_avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRedtZJWpoSrUa4XYLEcDAdFPRAXaw6QDgFiTHSNrMcjQ&s',

    ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Ana Pérez');

    $this->assertDatabaseHas('contacts', [
        'name' => 'Ana Pérez',
        'email' => 'ana@example.com',
        'address' => 'Calle 123, Ciudad',
        'phone_number' => '1234567890',
        'url_avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRedtZJWpoSrUa4XYLEcDAdFPRAXaw6QDgFiTHSNrMcjQ&s',
        'user_id' => $user->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
});

it('valida que el nombre sea obligatorio', function () {
    autenticado();

    $this->postJson('/api/v1/contacts', ['email' => 'ana@example.com'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

it('muestra un contacto propio', function () {
    $user = autenticado();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $this->getJson("/api/v1/contacts/{$contact->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $contact->id);
});

it('no deja ver el contacto de otro usuario', function () {
    autenticado();
    $ajeno = Contact::factory()->create();

    $this->getJson("/api/v1/contacts/{$ajeno->id}")->assertForbidden();
});

it('actualiza un contacto propio', function () {
    $user = autenticado();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $this->patchJson("/api/v1/contacts/{$contact->id}", ['name' => 'Nuevo nombre'])
        ->assertOk()
        ->assertJsonPath('data.name', 'Nuevo nombre');

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'updated_by' => $user->id,
    ]);
});

it('no deja editar el contacto de otro usuario', function () {
    autenticado();
    $ajeno = Contact::factory()->create();

    $this->patchJson("/api/v1/contacts/{$ajeno->id}", ['name' => 'Hack'])->assertForbidden();
});

it('borra con soft delete y guarda deleted_by', function () {
    $user = autenticado();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $this->deleteJson("/api/v1/contacts/{$contact->id}")->assertNoContent();

    $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'deleted_by' => $user->id,
    ]);
});

it('no deja borrar el contacto de otro usuario', function () {
    autenticado();
    $ajeno = Contact::factory()->create();

    $this->deleteJson("/api/v1/contacts/{$ajeno->id}")->assertForbidden();
});
