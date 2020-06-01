<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
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
            'email' => 'admin@admin.com',
            'role' => '-1',
            'status' => '1',
            'gender' => '1',
            'password' => bcrypt('admin'),
        ]);
    }
}
