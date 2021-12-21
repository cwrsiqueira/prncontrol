<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'company_id' => 1,
            'permission_group_id' => 1,
            'name' => 'Developer',
            'email' => 'cwrsiqueira@hotmail.com',
            'password' => Hash::make('password'),
            'inactive' => 0,
            'avatar' => 'default.jpg'
        ]);
    }
}
