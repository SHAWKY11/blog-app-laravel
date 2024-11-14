<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Laratrust\Models\Role;
use Illuminate\Database\Seeder;
use Laratrust\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\LaratrustSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LaratrustSeeder::class);

        // Create admin User and assign the role to him.
        $user = User::create([
            'name' => 'shawky',
            'email' => 'shawky@app.com',
            'password' => Hash::make('123456'),
            //'roles_name' => "owner",
        ]);

        $user->addRole('owner');
    }
}
