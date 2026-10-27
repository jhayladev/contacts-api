<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@contacts.test',
        ]);

        $collaborators = User::factory(5)->create();

        $collaborators->push($admin)->each(function (User $user) {
            Contact::factory(5)
                ->for($user)
                ->create();
        });
    }
}
