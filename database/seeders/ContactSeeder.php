<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->limit(10)->get()->each(function (User $user) {
            Contact::factory(3)->create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'deleted_by' => null,
            ]);
        });
    }
}
