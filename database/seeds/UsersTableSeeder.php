<?php

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
        DB::table('users')->insert([
            'name' => 'Reza D',
            'email' => 'daulayreza@gmail.com',
            'password' => bcrypt('123456'),
            'active' => 1
        ]);
    }
}
