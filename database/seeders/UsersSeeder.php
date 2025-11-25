<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $user = User::updateOrCreate(
            ['email' => 'dancanokeyo08@gmail.com'],
            [
                'name'              => 'Dancan Okeyo',
                'phone'             => '0711317235',
                'password'          => Hash::make('0711317235'),
                'code'              => 1234,
                'slug'              => Str::slug('Dancan Okeyo'),
                'email_verified_at' => null,
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );


        $user->syncRoles([]);


        $user->assignRole('super_admin');
    }
}
