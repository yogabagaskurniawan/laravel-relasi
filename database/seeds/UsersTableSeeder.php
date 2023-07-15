<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = [
            'name' => 'Admin',
            'email' => 'admin@excemple.com',
            'password' => bcrypt('admin'),
        ];

        if (!User::where('email', $adminUser['email'])->exists()){
            User::create($adminUser);
        }
    }
}
