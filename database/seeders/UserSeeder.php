<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lord Sidious',
            'email' => 'emperor@galacticempire.com',
            'password' => Hash::make('12345678')
        ]);
        User::create(
        [
            'name' => 'Lord Vader',
            'email' => 'lordvader@galacticempire.com',
            'password' => Hash::make('12345678')
        ]);
        User::create(
        [
            'name' => 'Lord Tyranus',
            'email' => 'countdooku@separatists.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
