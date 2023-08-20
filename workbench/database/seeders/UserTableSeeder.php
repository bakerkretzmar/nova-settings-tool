<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => $password,
        ]);

        User::forceCreate([
            'name' => 'James Brooks',
            'email' => 'james@laravel.com',
            'password' => $password,
        ]);

        User::forceCreate([
            'name' => 'David Hemphill',
            'email' => 'david@laravel.com',
            'password' => $password,
        ]);

        User::forceCreate([
            'name' => 'Laravel Nova',
            'email' => 'nova@laravel.com',
            'password' => $password,
        ]);
    }
}
