<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultiUserSeeder extends Seeder
{
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Daffa Rosyadi',
                'username' => 'admin',
                'phone' => '081234512345',
                'role' => 'Admin',
                'email' => 'admin123@gmail.com',
                'password' => Hash::make('admin')
            ],
            // [
            //     'name' => 'Fadhillah Dinurahman',
            //     'username' => 'fadhillah_dnr',
            //     'phone' => '085432154321',
            //     'role' => 'User',
            //     'email' => 'fadhillah_dnr123@gmail.com',
            //     'password' => Hash::make('fadhillah_dnr')
            // ]
        ];

        foreach ($userData as $user) {
            User::create($user);
        }
    }
}