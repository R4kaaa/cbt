<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create user
        User::create([
            'name'      => 'Administrator',
            'email'     => 'guru5@gmail.com',
            'password'  => bcrypt('Bismillah5'),
        ]);
    }
}
