<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
        'name' => 'Admin',
        'email' => 'admin@yejsira.com',
        'password' => Hash::make('admin123'), // change later for security
        'is_admin' => true,
    ]);
    }
}
