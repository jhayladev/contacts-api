<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contact;

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
