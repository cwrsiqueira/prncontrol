<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permission_groupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_groups')->insert([
            'company_id' => 1,
            'name' => 'developer'
        ]);
        DB::table('permission_groups')->insert([
            'company_id' => 1,
            'name' => 'admin'
        ]);
        DB::table('permission_groups')->insert([
            'company_id' => 1,
            'name' => 'user'
        ]);
        DB::table('permission_groups')->insert([
            'company_id' => 1,
            'name' => 'consultant'
        ]);
    }
}
