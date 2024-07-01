<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory(5)->create();
        User::factory(5)->create();
        User::factory([
            'email' => 'defaultuser@buckhill.co.uk',
            'password' => Hash::make('userpassword'), // password
        ])->create();
    }
}
