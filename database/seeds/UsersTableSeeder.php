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
        //
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'ah4d1an@gmail.com',
            'password' => app('hash')->make('123456789'),
        ]);
    }
}
